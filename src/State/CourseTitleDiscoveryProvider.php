<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\CourseTitle;
use App\Repository\CourseTitleRepository;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @implements ProviderInterface<CourseTitle>
 */
class CourseTitleDiscoveryProvider implements ProviderInterface
{
    private CourseTitleRepository $repository;
    private RequestStack $requestStack;

    public function __construct(CourseTitleRepository $repository, RequestStack $requestStack)
    {
        $this->repository = $repository;
        $this->requestStack = $requestStack;
    }

    /**
     * @return CourseTitle[]|object|object[]|null
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array|object|null
    {
        $request = $this->requestStack->getCurrentRequest();

        $search = $request->query->get('search') ?? '';
        $tag = $request->query->get('tag');
        $semester = $request->query->get('semester');

        if ($tag) {
            $courseTitles = $this->repository->findForSemesterFromCurrentYearAndTag($search, $tag, $semester);
        } else {
            $courseTitles = $this->repository->findForSemesterFromCurrentYear($search, $semester);
        }

        return $courseTitles;
    }
}
