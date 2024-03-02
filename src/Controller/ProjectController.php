<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Project;

/**
 * @Route("/api", name="api_")
 */
class ProjectController extends AbstractController {
    /**
     * @Route("/projects", name="project_index", methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine): JsonResponse {
        $projects = $doctrine
            ->getRepository(Project::class)
            ->findAll();

        $data = [];

        foreach ($projects as $project) {
            $data[] = [
                'id' => $project->getId(),
                'name' => $project->getName(),
                'description' => $project->getDescription()
            ];
        }
        return $this->json($data);
    }

    /**
     * @Route("/projects", name="project_create", methods={"POST"})
     */
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse {
        $entityManager = $doctrine->getManager();

        $project = new Project();
        $project->setName($request->request->get('name'));
        $project->setDescription($request->request->get('description'));

        $entityManager->persist($project);
        $entityManager->flush();

        $data = [
            'id' => $project->getId(),
            'name' => $project->getName(),
            'description' => $project->getDescription()
        ];

        return $this->json($data);
    }

    /**
     * @Route("/projects/{id}", name="project_getProjectById", methods={"GET"})
     */
    public function getProjectById(ManagerRegistry $doctrine, int $id): JsonResponse {
        $project = $doctrine
            ->getRepository(Project::class)
            ->find($id);

        if (! $project) {
            return $this->json('No project found for id ' . $id, 404);
        }

        $data[] = [
            'id' => $project->getId(),
            'name' => $project->getName(),
            'description' => $project->getDescription()
        ];

        return $this->json($data);
    }
}
