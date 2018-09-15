<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Repository\SubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/subject")
 */
class SubjectController extends Controller
{
    /**
     * @Route("/", name="subject_index", methods="GET")
     */
    public function index(SubjectRepository $subjectRepository): Response
    {
        $subjects = $subjectRepository->findBy([], [
            'year' => 'ASC',
            'name' => 'ASC',
        ]);
        return $this->render('subject/index.html.twig', ['subjects' => $subjects]);
    }

    /**
     * @Route("/enrolled", name="subject_enrolled", methods="GET")
     */
    public function enrolledIndex(SubjectRepository $subjectRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $subjects = $subjectRepository->createQueryBuilder('subject')
            ->leftJoin('subject.students', 'students')
            ->where('students.id = :student')
            ->orderBy('subject.year', 'ASC')
            ->addOrderBy('subject.name', 'ASC')
            ->setParameter('student', $this->getUser())
            ->getQuery()
            ->execute()
        ;

        return $this->render('subject/enrolled.html.twig', ['subjects' => $subjects]);
    }

    /**
     * @Route("/{id}", name="subject_show", methods="GET")
     */
    public function show(Subject $subject): Response
    {
        return $this->render('subject/show.html.twig', [
            'subject' => $subject,
            'tasks' => $subject->getTasks(),
        ]);
    }

    /**
     * @Route("/{id}/enroll", name="subject_enroll", methods="GET")
     */
    public function enroll(Subject $subject): Response
    {
        $this->denyAccessUnlessGranted('ENROLL', $subject);

        $subject->addStudent($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($subject);
        $em->flush();

        $this->addFlash('success', "Has sido matriculado en ${subject}");

        return $this->redirectToRoute('subject_index');
    }

    /**
     * @Route("/{id}/unenroll", name="subject_unenroll", methods="GET")
     */
    public function unenroll(Subject $subject): Response
    {
        $this->denyAccessUnlessGranted('UNENROLL', $subject);

        $subject->removeStudent($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($subject);
        $em->flush();

        $this->addFlash('success', "Has sido desmatriculado en ${subject}");

        return $this->redirectToRoute('subject_index');
    }
}
