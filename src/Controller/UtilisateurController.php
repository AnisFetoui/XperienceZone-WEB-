<?php

namespace App\Controller;

    use App\Entity\Utilisateur;
    use App\Entity\Userr;
    use App\Form\UtilisateurType;
    use App\Form\UserrType;
    use App\Form\UserModifType;
    use App\Form\UserProfileType;
    use Knp\Component\Pager\PaginatorInterface;
    use App\Repository\UserrRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use Symfony\Component\HttpFoundation\BinaryFileResponse;
    use App\Form\SortingFormType;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Endroid\QrCode\Writer\PngWriter;
    use Endroid\QrCode\QrCode;


    #[Route('/utilisateur')]
    class UtilisateurController extends AbstractController
    {
        #[Route('/', name: 'app_utilisateur_index', methods: ['GET'])]
        public function index(
            Request $request,
            UserrRepository $userRepository,
            PaginatorInterface $paginator
        ): Response {
          
            $query = $userRepository->findAll();
    
            $itemsPerPage = 5;
            $pagination = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                $itemsPerPage
            );
    
            return $this->render('utilisateur/index.html.twig', [
                'users' => $pagination,
            ]);
        }
        #[Route('/search', name: 'user_search', methods: ['GET'])]
public function search(Request $request, UserrRepository $userRepository): JsonResponse
{
    $searchString = $request->query->get('q');
    $users = $userRepository->findUsersByString($searchString);

    $userDetails = [];
    foreach ($users as $user) {
        $userDetails[] = [
            'idUser' => $user->getIdUser(),
            'username' => $user->getMail(),
        ];
    }

    return new JsonResponse(['users' => $userDetails]);
}

        #[Route('/searchAnis', name: 'app_users_search')]
        public function searchPage(): Response
        {
            return $this->render('utilisateur/search.html.twig');
        }
        #[Route('/details/{idr}', name: 'app_serachAnis_details', methods: ['GET'])]
        public function details(string $idr, UserrRepository $UserRepository): Response
        {
            $user = $UserRepository->find($idr);
        
            return $this->render('utilisateur/detailSearch.html.twig', [
                'user' => $user,
            ]);
        }
        
        
        #[Route('/generateExcel', name: 'excel')]
        public function generateExcel(UserrRepository $evepo): BinaryFileResponse
{
    $evenements = $evepo->findAll();
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'IdUSer');
    $sheet->setCellValue('B1', 'Username');
    $sheet->setCellValue('C1', 'Mail');
    $sheet->setCellValue('D1', 'Password');
   // $sheet->setCellValue('E1', 'role');
    $sheet->setCellValue('E1', 'age');
    $sheet->setCellValue('F1', 'sexe');
    $sheet->setCellValue('G1', 'etat');

    $sn = 2;
    foreach ($evenements as $p) {
        $sheet->setCellValue('A' . $sn, $p->getIdUser());
        $sheet->setCellValue('B' . $sn, $p->getUsername());
        $sheet->setCellValue('C' . $sn, $p->getMail());
        $sheet->setCellValue('D' . $sn, $p->getPassword());
    //    $sheet->setCellValue('A' . $sn, $p->getRoles());
        $sheet->setCellValue('E' . $sn, $p->getAge());
        $sheet->setCellValue('F' . $sn, $p->getSexe());
        $sheet->setCellValue('G' . $sn, $p->isEtat());

        $sn++;
    }

    $writer = new Xlsx($spreadsheet);

    $fileName = 'Users.xlsx';
    $temp_file = tempnam(sys_get_temp_dir(), $fileName);

    $writer->save($temp_file);

    return new BinaryFileResponse($temp_file, 200, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Content-Disposition' => sprintf('inline; filename="%s"', $fileName),
    ]);
}
    

    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new Userr();
        $form = $this->createForm(UserrType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur->setRoles(['CLIENT']);
          //  $utilisateur->setResetToken("anis");
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

   
    #[Route('/{idUser}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(Userr $utilisateur): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{idUser}/editProfileA', name: 'app_ProfileAdmin', methods: ['GET', 'POST'])]
    public function ProfileAdmin(Request $request, Userr $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserProfileType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('utilisateur/ProfileAdmin.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    
    #[Route('/{idUser}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Userr $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserModifType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{idUser}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Userr $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getIdUser(), $request->request->get('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/tri/{criteria}', name: 'app_user_tri', methods: ['GET'])]
    public function tri(UserrRepository $userRepository, PaginatorInterface $paginator, Request $request, string $criteria): Response
    {
        $validCriteria = ['username', 'mail','age'];
        
    
        if (!in_array($criteria, $validCriteria)) {
            throw $this->createNotFoundException('Invalid sorting criteria.');
        }
    
        $queryBuilder = $userRepository->createQueryBuilder('u')->orderBy('u.' . $criteria, 'ASC');
        $pagination = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 6);
    
        return $this->render('utilisateur/index.html.twig', [
            'users' => $pagination,
            'currentCriteria' => $criteria,
        ]);
    }


#[Route('/load-user-content/{iduser}', name: 'load_user_content', methods: ['GET'])]
public function loadUserContent(UserrRepository $userRepository, $iduser): Response
{   
    $writer = new PngWriter();

    $user = $userRepository->find($iduser);
    $userData = $user->getUserDataForQrCode();
    $qrCode = new QrCode($userData);

    $pngResult = $writer->write($qrCode);
    $qrCodeImage = base64_encode($pngResult->getString());

    return $this->render('utilisateur/qr.html.twig', [  
        'user'        => $user,
        'qrCodeImage' => $qrCodeImage,
    ]);
}



}
