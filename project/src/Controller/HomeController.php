<?php
/**
 * @author: Cristian Pana
 * Date: 14.11.2020
 */

namespace CPANA\App\Controller;

use CPANA\App\Entity\CachingLog;
use CPANA\App\Entity\Image;
use CPANA\App\Entity\Record;
use CPANA\App\Utils\InputDataParser;
use CPANA\App\Utils\RecordFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Render home page
     * @Route(name="home", path="/")
     */
    public function home(EntityManagerInterface $em)
    {
        // Count total rows processed
        $totalRowsProcessed = $em->getRepository(CachingLog::class)->countRows();
        $recordsCounter     = $em->getRepository(Record::class)->countRows();
        $imagesCounter      = $em->getRepository(Image::class)->countRows();

        return $this->render('home/home.html.twig',[
            'total_rows_processed' => $totalRowsProcessed,
            'records_counter' => $recordsCounter,
            'images_counter'  => $imagesCounter,
        ]);
    }

    /**
     * Data import:
     *  - insert info directly in DB
     *  - add tasks in RabbitMQ queue for each image to be cached
     *
     * @Route(name="import_data", path="/import_data", methods={"POST"})
     */
    public function importData(InputDataParser $parser, EntityManagerInterface $em, MessageBusInterface $bus, string $cacheFolder, string $publicDir)
    {
        try {
            // Clean previous data
            $em->getRepository(CachingLog::class)->cleanPreviousData();
            $command = 'rm -rf "'.$publicDir.'/'.$cacheFolder.'"/{*,.[!.]*} 2>&1';
            exec($command,$output, $existStatus);
            if($existStatus !== 0) throw new \Exception("Cannot remove cache folder content! Output:".json_encode($output)." exist status:".$existStatus);

            // Read and parse JSON data source
            $data = $parser->getDataAndStats();
        } catch (\Throwable $e) {
            return new JsonResponse(['status'=> 0, 'data' => [], 'error' => $e->getMessage()], 200);
        }

        // For batch inserts disable SQL logger
        // https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/reference/batch-processing.html
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        $batchSize = 25;
        $records = [];
        $i = 0;
        foreach ($data['raw_data'] as $recordRaw) {
            $records[$i] = RecordFactory::createFromRawData($recordRaw);
            $em->persist($records[$i]);
            if (($i % $batchSize) === 0) {
                $em->flush();
                $em->clear(); // Detaches all objects from Doctrine!
            }
        }
        $em->flush(); //Persist objects that did not make up an entire batch
        $em->clear();


        return new JsonResponse([
            'status'=> 1,
            'data' => [
                'records_counter' => $data['records_counter'],
                'images_counter'  => $data['images_counter'],
                'raw'             => $data['raw_data']
            ]],
            200
        );
    }

    /**
     * Progressively display processed info as they are inserted in caching_log
     *
     * @Route(name="update_caching_log_table", path="/update_caching_log_table", methods={"POST"})
     */
    public function updateCachingLogTable(Request $request, EntityManagerInterface $em)
    {
        // Get last row from POST request content
        $parameters = json_decode($request->getContent(), true, JSON_THROW_ON_ERROR);
        if (!empty($parameters['lastRow'])) {
            $lastRow = intval($parameters['lastRow']);
        } else {
            $lastRow = 0;
        }

        $rows = $em->getRepository(CachingLog::class)->getRecordsAfterLastRow($lastRow);

        // New lines were added, update new last row
        if(isset($rows[array_key_last($rows)]['id'])) {
            $newLastRow = intval($rows[array_key_last($rows)]['id']);
        } else {
            //last row didn't changed
            $newLastRow = $lastRow;
        }
        // Count total rows processed
        $totalRowsProcessed = $em->getRepository(CachingLog::class)->countRows();

        return new JsonResponse([
            'status'=> 1,
            'data' => [
                'last_row' => $newLastRow,
                'rows'  => $rows,
                'total_rows_processed' => $totalRowsProcessed
            ]],
            200
        );

    }


}
