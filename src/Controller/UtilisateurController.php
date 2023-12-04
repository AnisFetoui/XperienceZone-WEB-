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
    use App\Repository\ActivitesRepository;
    use App\Repository\ProduitRepository;

    use App\Repository\InscriptionRepository;
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
    use Doctrine\Persistence\ObjectManager;


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
        #[Route('/back', name: 'activitesback_index', methods: ['GET'])]
    public function backofficeact(ActivitesRepository $activitesRepository, InscriptionRepository $inscriptionRepository): Response
    {
        return $this->render('activites/backoffice.html.twig', [
            'activites' => $activitesRepository->findAll(),
            'inscriptions' => $inscriptionRepository->findAll(),
            
        ]);
    }

    #[Route('/backProduit', name: 'produitback_index', methods: ['GET'])]
    public function backofficeprod(Request $request,ProduitRepository $produitRepository,PaginatorInterface $paginator): Response
    {
        $criteria = $request->query->get('criteria', 'idProd'); 
            
            
            $validCriteria = ['idProd', 'nomProd', 'DescriptionProd', ]; 
            
            if (!in_array($criteria, $validCriteria)) {
                $criteria = 'idProd';
            }
    
            $produits = $produitRepository->findBy([], [$criteria => 'ASC']);
            $produitsQuery=$produits;
            //$produitsQuery = $produitRepository->createQueryBuilder('p')->getQuery();
        
            $produits = $paginator->paginate(
                $produitsQuery,
                $request->query->getInt('page', 1),
                5 
            );
        
            if ($request->query->get('excel')) {
                
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
        
                $sheet->setCellValue('A1', 'ID')->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFCC00']],
                ]);
                $sheet->setCellValue('B1', 'Nom')->getStyle('B1')->applyFromArray(['font' => ['bold' => true]]);
                $sheet->setCellValue('C1', 'Prix')->getStyle('C1')->applyFromArray(['font' => ['bold' => true]]);
                $sheet->setCellValue('D1', 'Description')->getStyle('D1')->applyFromArray(['font' => ['bold' => true]]);
                $sheet->setCellValue('E1', 'Quantité')->getStyle('E1')->applyFromArray(['font' => ['bold' => true]]);
                $sheet->setCellValue('F1', 'Catégorie')->getStyle('F1')->applyFromArray(['font' => ['bold' => true]]);
                $sheet->setCellValue('G1', 'Note')->getStyle('G1')->applyFromArray(['font' => ['bold' => true]]);
        
                $row = 2;
                foreach ($produits as $produit) {
                    $sheet->setCellValue('A' . $row, $produit->getIdProd());
                    $sheet->setCellValue('B' . $row, $produit->getNomProd());
                    $sheet->setCellValue('C' . $row, $produit->getPrixProd());
                    $sheet->setCellValue('D' . $row, $produit->getDescriptionProd());
                    $sheet->setCellValue('E' . $row, $produit->getQuantite());
                    $sheet->setCellValue('F' . $row, $produit->getCategorie()->getNomCategorie()); 
                    $sheet->setCellValue('G' . $row, $produit->getNoteProd());
        
                    $style = $row % 2 == 0 ? ['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']]] : [];
                    $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray($style);
        
                    $row++;
                }
        
                foreach (range('A', 'G') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
        
                
                $writer = new Xlsx($spreadsheet);
                $excelFilePath = tempnam(sys_get_temp_dir(), 'produits_export') . '.xlsx';
                $writer->save($excelFilePath);
        
                
                $response = new Response(file_get_contents($excelFilePath));
                $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                $response->headers->set('Content-Disposition', 'attachment;filename="produits_export.xlsx"');
                $response->headers->set('Cache-Control', 'max-age=0');
                $response->headers->set('Pragma', 'public');
        
                unlink($excelFilePath); 
        
                return $response;
            }
        
            return $this->render('produit/indexadmin.html.twig', [
                'produits' => $produits,
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
    public function show(UserrRepository $UserRepository,$idUser): Response
    {
        $utilisateur = $UserRepository->find($idUser);
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{idUser}/editProfileA', name: 'app_ProfileAdmin', methods: ['GET', 'POST'])]
    public function ProfileAdmin(Request $request,$idUser,UserrRepository $UserRepository, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $UserRepository->find($idUser);
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
