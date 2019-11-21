<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ProjectType;
use App\Entity\User;
use App\Entity\Project;

class ProjectController extends AbstractController
{
    /**
     * list projects
     * 
     * @Route("/projects", name="app_projects")
     * @return Response
     */
    public function index(): Response
    {
        /** @var Project $projects */
        $projects = $this->getDoctrine()->getManager()->getRepository(Project::class)->findAll();

        return $this->render('project/index.html.twig', [
            'projects' => $projects
        ]);
    }

    /**
     * new project
     * 
     * @Route("/project/new", name="project_new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                //pega o usuario logado no momento, vem do abstractController
                /** @var User $user */
                $user = $this->getUser();
                $user->addProject($project);

                $em = $this->getDoctrine()->getManager();

                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('app_projects');
            } catch (\Exception $e) {
                $form->addError(new FormError($e->getMessage()));
            }
        }

        return $this->render('project/new.html.twig', [
            'newProjectForm' => $form->createView(),
        ]);
    }

    /**
     * show a project by a given id
     *
     * @Route("/project/view/{project}", name="project_view")
     * @param Project $project
     * @return Response
     */
    public function show(Project $project): Response
    {
        return $this->render('project/view.html.twig', [
            'project' => $project
        ]);
    }

    /**
     * edit a project
     *
     * @Route("/project/edit/{project}", name="project_edit")
     * 
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function edit(Request $request, Project $project): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('project_view', [
                    'project' => $project->getId()
                ]);
            } catch (\Exception $e) {
                $form->addError(new FormError($e->getMessage()));
            }
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'projectEditForm' => $form->createView()
        ]);
    }
}
