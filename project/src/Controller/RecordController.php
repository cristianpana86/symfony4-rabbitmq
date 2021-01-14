<?php

namespace CPANA\App\Controller;

use CPANA\App\Entity\Record;
use CPANA\App\Form\RecordType;
use CPANA\App\Repository\RecordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/records")
 */
class RecordController extends AbstractController
{
    /**
     * @Route("/", name="record_index", methods={"GET"})
     */
    public function index(RecordRepository $recordRepository): Response
    {
        return $this->render('record/index.html.twig', [
            'records' => $recordRepository->findAll(),
        ]);
    }


    /**
     * @Route("/{id}", name="record_show", methods={"GET"})
     */
    public function show(Record $record): Response
    {
        return $this->render('record/show.html.twig', [
            'record' => $record,
        ]);
    }
}
