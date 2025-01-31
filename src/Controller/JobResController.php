<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\ResJob;
use App\Form\ResJobType;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("jobRes")
 */
class JobResController extends AbstractController
{
    /**
     * @Route("/", name="app_job_res_index")
     */
    public function index(JobRepository $jobRepository, Request $request): Response
    {
        return $this->render('test/index.html.twig', [
            'jobs' => $jobRepository->findAll(),

        ]);
    }

    /**
     * @Route("/new/{id}", name="app_job_res_new", methods={"GET", "POST"})
     */
    public function new(Request $request, Job $job, EntityManagerInterface $entityManager): Response
    {
        $jobRes = new ResJob();

        if ($request->getMethod() == 'POST') {
            $jobRes = new ResJob();
            $jobRes->setJob($job);
            $jobRes->setNom($request->request->get('nom'));
            $jobRes->setPrenom($request->request->get('prenom'));
            $jobRes->setEmail($request->request->get('email'));
            $jobRes->setTelephone($request->request->get('tel'));
            $jobRes->setAdress($request->request->get('adress'));

            $file = $request->files->get('file');
            $fileName = $file->getClientOriginalName();


            $publicDirectory = $this->getParameter('file_directory');
            $filepath = $publicDirectory . "/" . $job->getId();
            if (!file_exists($filepath)) {
                mkdir($filepath, 0777, true);
            }
            $fileName = $file->getClientOriginalName();
            $file->move(
                $filepath,
                $fileName
            );
            $jobRes->setDiplome($fileName);
            $entityManager->persist($jobRes);
            $entityManager->flush();

        }

        return $this->redirectToRoute('app_job_res_index');
    }
}
