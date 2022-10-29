<?php

namespace App\Controller;

use App\Entity\Partner;
use App\Entity\Permission;
use App\Entity\Structure;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PartnerRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\UserType;
use App\Form\PermissionsType;
use App\Form\PaertnerPermissionsType;
use App\Form\StructurePermissionsType;
use App\Repository\PermissionRepository;
use App\Repository\StructureRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\SendEmailService;
use Symfony\Component\HttpFoundation\JsonResponse;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private SendEmailService $mailer
    ) {
    }


    #[Route('', name: 'index')]
    public function index(PartnerRepository $partner_repository): Response
    {
        return $this->render('admin/index.html.twig', [
            'partners' => $partner_repository->findAll(),
        ]);

    }

    #[Route('/add-partner', name: 'add_partner')]
    public function addPartner(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();     
            $plaintextPassword = $user->getPassword();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_PARTNER']);
            $em = $doctrine->getManager();
            $em->persist($user);
            $partner = new Partner();
            $partner->setUser($user);
            $em->persist($partner);
            $em->flush();
            try {
                $resetToken = $this->resetPasswordHelper->generateResetToken($user);
                $notif_template = 'reset_password/email.html.twig';
                $context = [
                    'resetToken' => $resetToken,
                    'type' => 'Partenaire',
                    'emailAdresse' => $user->getEmail()
                ];
                $subject = 'Changement mot de passe.';
                $this->mailer->sendEmail($user->getEmail(), $subject, $notif_template, $context);
            } catch (ResetPasswordExceptionInterface $e) {
    
                return $this->redirectToRoute('admin_index');
            }
            return $this->redirectToRoute('admin_index');
        }
        return $this->renderForm('admin/add_partner.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit-partner/{id}', name: 'edit_partner')]
    public function editPartner(Request $request, User $partner, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $partner, [
            'is_edit' => true
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {            
            $password = $form->get('password')->getData();
            if($password) {
                $hashedPassword = $passwordHasher->hashPassword(
                    $partner,
                    $password
                );
                $partner->setPassword($hashedPassword);
            }
            $em = $doctrine->getManager();
            $em->persist($partner);
            $em->flush();
            return $this->redirectToRoute('admin_index');
        }
        return $this->renderForm('admin/add_partner.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/show-partner-permissions/{id}', name: 'show_partner_permissions')]
    public function showPartnerPermissions(Partner $partner): Response
    {
        return $this->render('admin/partner/permissions/index.html.twig', [
            'partner' => $partner,
        ]);
    }

    #[Route('/add-partner-permissions/{id}', name: 'add_partner_permissions')]
    public function addPartnerPermissions(Request $request, Partner $partner, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(PaertnerPermissionsType::class, $partner);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $partner = $form->getData();
            $em = $doctrine->getManager();
            $em->persist($partner);
            $partner->updateStructurePermission($partner->getPermission());
            $em->flush();
            $notif_template = 'email/notif_new_permission.html.twig';
            $subject = 'Changement Permissions';
            $context = ['description' => 'Des changement sur vos permissions sont en ligne, veuillez vous connecter pour consulter les chagements.'];
            $this->mailer->sendEmail($partner->getUser()->getEmail(), $subject, $notif_template, $context);
            return $this->redirectToRoute('admin_show_partner_permissions', ['id' => $partner->getId()]);
        }
        return $this->renderForm('admin/partner/permissions/add.html.twig', [
            'form' => $form,
            'partner' => $partner
        ]);
    }

    #[Route('/disable-partner/{id}', name: 'disable_partner')]
    public function disablePartnerPermissions(Partner $partner, ManagerRegistry $doctrine): Response
    {
        $partner->getUser()->setDisable();;
        $partner->disableStructure();
        $em = $doctrine->getManager();
        $em->persist($partner);
        $em->flush();
        return $this->redirectToRoute('admin_index');
    }

    #[Route('/enable-partner/{id}', name: 'enable_partner')]
    public function enablePartnerPermissions(Partner $partner, ManagerRegistry $doctrine): Response
    {
        $partner->getUser()->setEnable();
        $partner->enableStructure();
        $em = $doctrine->getManager();
        $em->persist($partner);
        $em->flush();
        return $this->redirectToRoute('admin_index');
    }

    #[Route('/permissions', name: 'permissions')]
    public function showPermissions(PermissionRepository $permissions_repository): Response
    {
        return $this->render('admin/permissions/index.html.twig', [
            'permissions' => $permissions_repository->findAll(),
        ]);

    }

    #[Route('/add-permission', name: 'add_permission')]
    public function addPermission(Request $request, ManagerRegistry $doctrine): Response
    {
        $permission = new Permission();
        $form = $this->createForm(PermissionsType::class, $permission);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $permission = $form->getData();
            $em = $doctrine->getManager();
            $em->persist($permission);
            $em->flush();
            return $this->redirectToRoute('admin_permissions');
        }
        return $this->renderForm('admin/permissions/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit-permission/{id}', name: 'edit_permission')]
    public function editPermission(Request $request, Permission $permission, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(PermissionsType::class, $permission);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($permission);
            $em->flush();
            return $this->redirectToRoute('admin_permissions');
        }
        return $this->renderForm('admin/permissions/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/structure', name: 'structure')]
    public function showStructure(StructureRepository $structure_repository): Response
    {
        return $this->render('admin/structure/index.html.twig', [
            'structures' => $structure_repository->findAll(),
        ]);

    }

    #[Route('/add-structure', name: 'add_structure')]
    public function addStructure(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user,[
            'is_structure' => true
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $partner = $form->get('partner')->getData();
            $user = $form->getData();
            $plaintextPassword = $user->getPassword();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_STRUCTURE']);
            $em = $doctrine->getManager();
            $em->persist($user);
            $partner_permissions = $partner->getPermission();            
            $structure = new Structure();
            $structure->setUser($user);
            $structure->getUser()->setEnable();
            $structure->setPartner($partner);
            foreach($partner_permissions as $permission) {
                $structure->addPermission($permission);
            }
            $em->persist($structure);
            $em->flush();
            try {
                $resetToken = $this->resetPasswordHelper->generateResetToken($user);
                $notif_template = 'reset_password/email.html.twig';
                $context = [
                    'resetToken' => $resetToken,
                    'type' => 'Structure',
                    'emailAdresse' => $user->getEmail()
                ];
                $subject = 'Changement mot de passe.';
                $this->mailer->sendEmail($user->getEmail(), $subject, $notif_template, $context);
            } catch (ResetPasswordExceptionInterface $e) {
    
                return $this->redirectToRoute('admin_index');
            }
            $notif_template = 'email/notif_new_structure.html.twig';
                $context = [
                    'structure' => $user->getNom()
                ];
                $subject = 'Ajout de nouvelle structure.';
                $this->mailer->sendEmail($partner->getUser()->getEmail(), $subject, $notif_template, $context);
            return $this->redirectToRoute('admin_structure');
        }
        return $this->renderForm('admin/structure/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit-structure/{id}', name: 'edit_structure')]
    public function editStructure(Request $request, User $structure, ManagerRegistry $doctrine, StructureRepository $structure_repository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user_structure = $structure_repository->findOneBy(['user' => $structure->getId()]);
        $form = $this->createForm(UserType::class, $structure, [
            'is_edit' => true,
            'is_structure' => true,
            'partner_data' => $user_structure->getPartner()
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {            
            $password = $form->get('password')->getData();
            if($password) {
                $hashedPassword = $passwordHasher->hashPassword(
                    $structure,
                    $password
                );
                $structure->setPassword($hashedPassword);
            }
            $partner = $form->get('partner')->getData();
            $partner_permissions = $partner->getPermission();  
            $user_structure->setPartner($partner);
            $user_structure->clearPermissions();
            foreach($partner_permissions as $permission) {
                $user_structure->addPermission($permission);
            }
            $em = $doctrine->getManager();
            $em->persist($structure);
            $em->flush();
            return $this->redirectToRoute('admin_structure');
        }
        return $this->renderForm('admin/structure/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/show-structure-permissions/{id}', name: 'show_structure_permissions')]
    public function showStructurePermissions(Structure $structure, StructureRepository $structure_repository): Response
    {
        return $this->render('admin/structure/permissions/index.html.twig', [
            'structure' => $structure_repository->find($structure->getId()),
        ]);
    }

    #[Route('/add-structure-permissions/{id}', name: 'add_structure_permissions')]
    public function addStructurePermissions(Request $request, Structure $structure, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(StructurePermissionsType::class, $structure, [
            'permission_id' => $structure->getId()
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $permission = $form->getData();
            $em = $doctrine->getManager();
            $em->persist($permission);
            $em->flush();
            $notif_template = 'email/notif_new_permission.html.twig';
            $subject = 'Changement Permissions';
            $context_partner = ['description' => 'Des changement sur les permissions de votre structure <strong>'.$structure->getUser()->getNom().'</strong> sont en ligne, veuillez vous connecter pour consulter les chagements.'];
            $context = ['description' => 'Des changement sur vos permissions sont en ligne, veuillez vous connecter pour consulter les chagements.'];
            $this->mailer->sendEmail($structure->getUser()->getEmail(), $subject, $notif_template, $context);
            $this->mailer->sendEmail($structure->getPartner()->getUser()->getEmail(), $subject, $notif_template, $context_partner);
            return $this->redirectToRoute('admin_show_structure_permissions', ['id' => $structure->getId()]);
        }
        return $this->renderForm('admin/structure/permissions/add.html.twig', [
            'form' => $form,
            'structure' => $structure
        ]);
    }

    #[Route('/disable-structure/{id}', name: 'disable_structure')]
    public function disableStructure(Structure $structure, ManagerRegistry $doctrine): Response
    {
        $structure->getUser()->setDisable();
        $em = $doctrine->getManager();
        $em->persist($structure);
        $em->flush();
        return $this->redirectToRoute('admin_structure');
    }

    #[Route('/enable-structure/{id}', name: 'enable_structure')]
    public function enableStructure(Structure $structure, ManagerRegistry $doctrine): Response
    {
        $structure->getUser()->setEnable();
        $em = $doctrine->getManager();
        $em->persist($structure);
        $em->flush();
        return $this->redirectToRoute('admin_structure');
    }

    #[Route('/ajax-search-by-name/{type}/{name}', name: 'ajax_search_by_name', methods:'GET')]
    public function ajaxSearchByName($type,  $name, ManagerRegistry $doctrine): JsonResponse
    {
        if(!$type) {
            return new JsonResponse(['success' => false, 'message' => 'Une erreur s\'est produite']);
        }
        if(!$name) {
            return new JsonResponse(['success' => false, 'message' => 'Une erreur s\'est produite']);
        }
        $type_class = $type === 'partner' ? Partner::class : Structure::class;
        /** @var PartnerRepository | StructureRepository  */
        $em = $doctrine->getRepository($type_class)->searchByName($name);
        $result = $this->renderView('admin/ajax/search_by_name.html.twig', [
            'data' => $em,
            'type' => $type
        ]);
        return new JsonResponse(['success' => true, 'data' => $result]);
    }

    #[Route('/ajax-search-by-name-all/{type}', name: 'ajax_search_by_name_all', methods:'GET')]
    public function ajaxSearchByNameAll($type, ManagerRegistry $doctrine): JsonResponse
    {
        if(!$type) {
            return new JsonResponse(['success' => false, 'message' => 'Une erreur s\'est produite']);
        }
        $type_class = $type === 'partner' ? Partner::class : Structure::class;
        /** @var PartnerRepository | StructureRepository  */
        $em = $doctrine->getRepository($type_class)->findAll();
        $result = $this->renderView('admin/ajax/search_by_name.html.twig', [
            'data' => $em,
            'type' => $type
        ]);
        return new JsonResponse(['success' => true, 'data' => $result]);
    }

    #[Route('/ajax-filter-by-status/{type}/{status}', name: 'ajax_search_by_status', methods:'GET')]
    public function ajaxFilterbyStattus($type,  $status, ManagerRegistry $doctrine): JsonResponse
    {
        if(!$type) {
            return new JsonResponse(['success' => false, 'message' => 'Une erreur s\'est produite']);
        }
        $type_class = $type === 'partner' ? Partner::class : Structure::class;
        /** @var PartnerRepository | StructureRepository  */
        
        $em = ($status !== '-1')?$doctrine->getRepository($type_class)->filterByStatus($status) : $em = $doctrine->getRepository($type_class)->findAll();
        $result = $this->renderView('admin/ajax/search_by_name.html.twig', [
            'data' => $em,
            'type' => $type
        ]);
        return new JsonResponse(['success' => true, 'data' => $result]);
    }
    
}



