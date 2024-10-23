<?php

namespace App\Controller\Admin;

use App\Entity\Affectation;
use App\Entity\Course;
use App\Entity\CourseTitle;
use App\Entity\ExternalHourRecord;
use App\Entity\HourlyVolume;
use App\Entity\Module;
use App\Entity\Semester;
use App\Entity\Tag;
use App\Entity\TypeCourse;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('tableau de bord');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('User', 'fa-solid fa-user', User::class);
        yield MenuItem::linkToCrud('ExternalHoursRecord', 'fas fa-clock', ExternalHourRecord::class);
        yield MenuItem::linkToCrud('Affectation', 'fas fa-arrow-right', Affectation::class);
        yield MenuItem::linkToCrud('Course', 'fas fa-graduation-cap', Course::class);
        yield MenuItem::linkToCrud('CourseTitle', 'fas fa-font', CourseTitle::class);
        yield MenuItem::linkToCrud('HourlyVolume', 'fas fa-hourglass-half ', HourlyVolume::class);
        yield MenuItem::linkToCrud('Module', 'fas fa-list-alt ', Module::class);
        yield MenuItem::linkToCrud('Semester', 'fas fa-bookmark ', Semester::class);
        yield MenuItem::linkToCrud('Tag', 'fas fa-tag ', Tag::class);
        yield MenuItem::linkToCrud('TypeCourse', 'fas fa-folder ', TypeCourse::class);

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
