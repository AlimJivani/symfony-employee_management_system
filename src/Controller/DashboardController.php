<?php

namespace App\Controller;

use App\Entity\EmpDetails;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\User;
use App\Form\EmpDetailsType;
use App\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\RequestStack;
use Gedmo\Sluggable\Util\Urlizer;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;



class DashboardController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack, RoleHierarchyInterface $roleHierarchy)
    {
        $this->requestStack = $requestStack;
        $this->roleHierarchy = $roleHierarchy;
    }


    #[Route('/dashboard', name: 'dashboard')]
    public function showAll(UserRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $session = $this->requestStack->getSession();
        if (!$session->get('_csrf/https-authenticate')) {
            return $this->redirectToRoute('home');
        }

        $q = $request->query->get('q');
        $user = $repository->createQueryBuilder('emp')
            ->where('emp.deleteData = 0')
            ->andWhere('emp.firstName LIKE :searchEmp OR emp.lastName LIKE :searchEmp OR emp.email LIKE :searchEmp OR emp.mobileNumber LIKE :searchEmp')
            ->setParameter('searchEmp', '%' . $q . '%')
            ->getQuery();


        $pagination = $paginator->paginate(
            $user, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('dashboard/dashboard.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function deletEmployee($id): Response
    {

        $session = $this->requestStack->getSession();

        if (!$session->get('_csrf/https-authenticate')) {
            return $this->redirectToRoute('home');
        }

        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $roles = $user->getRoles();

        if ($this->isGranted($roles['0']) && $user) {
            $user->setDeleteData(1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard');
        }
        $this->addFlash('notAccess', 'Access Denied');
        return $this->redirectToRoute('dashboard');
    }

    /**
     * @Route("addemp", name="addemp")
     */
    public function addEmp(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {

        $session = $this->requestStack->getSession();

        if (!$session->get('_csrf/https-authenticate')) {
            return $this->redirectToRoute('home');
        }

        // Login Roles set with old Symfony
        $user = new User();
        $allRoles = $this->getParameter('security.role_hierarchy.roles');
        $loginUserRoles = $this->getUser()->getRoles();


        $form = $this->createForm(EmpDetailsType::class, $user, ['roles' => $allRoles[$loginUserRoles[0]]]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $user->setDeleteData(0);

            // Image uplode
            $uploadedFile = $form['EmpDetails']['userImg']->getData();
            $destination = $this->getParameter('kernel.project_dir') . '/public/profile';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = Urlizer::urlize($originalFilename) . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
            $uploadedFile->move($destination, $newFilename);
            $user->getEmpDetails()->setUserImg($newFilename);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('dashboard');
        }
        return $this->render('registration/addNewEmp.html.twig', [
            'addNewEmp' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function editEmployee($id, Request $request): Response
    {

        $session = $this->requestStack->getSession();

        if (!$session->get('_csrf/https-authenticate')) {
            return $this->redirectToRoute('home');
        }

        // Login Roles set with New Symfony
        $user = $this->getUser();
        $loginUserRoles = ($user->getRoles());
        $allRoles = $this->roleHierarchy->getReachableRoleNames($loginUserRoles);

        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if ($user) {
            $roles = $user->getRoles();
            if (!$this->isGranted($roles['0'])) {

                $this->addFlash('notAccess', 'Access Denied');
                return $this->redirectToRoute('dashboard');
            }
        }

        $form = $this->createForm(EmpDetailsType::class, $user, ['roles' => $allRoles]);
        $form->remove('password');
        $form->remove('email');

        if ($request->getMethod() == "POST" ) {

            if($form->isSubmitted() && $form->isValid()){
                $form->handleRequest($request);
                $data = $form->getData();

                // Image uplode
                $uploadedFile = $form['EmpDetails']['userImg']->getData();
                $destination = $this->getParameter('kernel.project_dir') . '/public/profile';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFilename) . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
                $uploadedFile->move($destination, $newFilename);
                $user->getEmpDetails()->setUserImg($newFilename);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($data);
                $entityManager->flush();
            }
           
        }

        return $this->render('registration/addNewEmp.html.twig', [
            'addNewEmp' => $form->createView(),
            'user' => $user
        ]);

    }

    /**
     * @Route("home", name="home")
     */
    public function home(): Response
    {

        return $this->render('home.html.twig');
    }
}
