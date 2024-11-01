<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LevelRepository;
use App\Repository\MainTeacherRepository;
use App\Entity\Level;
use App\Form\LevelType;
use App\Service\SchoolYearService;

/**
 * Level controller.
 *
 * @Route("/admin/levels")
 */
class LevelController extends AbstractController
{
    private $em;
    private SchoolYearService $schoolYearService;
    private MainTeacherRepository $mainTeacherRepo;

    public function __construct( MainTeacherRepository $mainTeacherRepo,SchoolYearService $schoolYearService,EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->schoolYearService = $schoolYearService;
        $this->mainTeacherRepo = $mainTeacherRepo;

    }

    /**
     * Lists all Programme entities.
     *
     * @Route("/", name="admin_levels")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(LevelRepository $repo)
    {

        $levels = $repo->findAll();

        return $this->render('level/index.html.twig', compact("levels"));
    }




    /**
     * @Route("/create",name="admin_levels_new", methods={"GET","POST"})
     */
    public function create(Request $request): Response
    {
        $level = new Level();
        $form = $this->createForm(LevelType::class, $level);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($level);
            $this->em->flush();
            $this->addFlash('success', 'Level succesfully created');
            return $this->redirectToRoute('admin_levels');
        }
        return $this->render(
            'level/new.html.twig',
            ['form' => $form->createView()]
        );
    }


    /**
     * Finds and displays a Level entity.
     *
     * @Route("/{id}/show", name="admin_levels_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Level $level)
    {

        $year = $this->schoolYearService->sessionYearById();
        $mainTeachers =  $this->mainTeacherRepo->findBy(array("schoolYear" => $year));
        $mainTeachersMap = array();
        foreach($mainTeachers as $mt){
            $mainTeachersMap[$mt->getClassRoom()->getId()] = $mt->getTeacher();
        }
        return $this->render('level/show.html.twig', compact("level", "mainTeachersMap"));
    }

    /**
     * Creates a new Level entity.
     *
     * @Route("/create", name="admin_levels_create")
     * @Method("POST")
     * @Template("AppBundle:Level:new.html.twig")
     */
    public function createAction(Request $request)
    {
        if (!$this->getUser()) {
            $this->addFlash('warning', 'You need login first!');
            return $this->redirectToRoute('app_login');
        }
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('warning', 'You need to have a verified account!');
            return $this->redirectToRoute('app_login');
        }
        $level = new Level();
        $form = $this->createForm(new LevelType(), $level);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($level);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_levels'));
        }

        return array(
            'level' => $level,
            'form'   => $form->createView(),
        );
    }


    /**
     * Displays a form to edit an existing Programme entity.
     *
     * @Route("/{id}/edit", name="admin_levels_edit", requirements={"id"="\d+"}, methods={"GET","PUT"})
     * @Template()
     */
    public function edit(Request $request, Level $level): Response
    {
        $form = $this->createForm(LevelType::class, $level, [
            'method' => 'PUT'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Level succesfully updated');
            return $this->redirectToRoute('admin_levels');
        }
        return $this->render('level/edit.html.twig', [
            'level' => $level,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a Programme entity.
     *
     * @Route("/{id}/delete", name="admin_levels_delete", requirements={"id"="\d+"}, methods={"DELETE"})
     */
    public function delete(Level $level, Request $request): Response
    {
        if ($this->isCsrfTokenValid('levels_deletion' . $level->getId(), $request->request->get('csrf_token'))) {
            $this->em->remove($level);

            $this->em->flush();
            $this->addFlash('info', 'Level succesfully deleted');
        }

        return $this->redirectToRoute('admin_levels');
    }
}
