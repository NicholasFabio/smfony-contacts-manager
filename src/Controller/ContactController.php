<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Entity\Contact;
use App\Entity\User;
use App\Repository\ContactRepository;

class ContactController extends AbstractController
{  
    private $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * @Route("/", name="list")
     */
    public function index(ManagerRegistry $doctrine, ?User $user): Response
    {   
        $contacts = $doctrine->getRepository(Contact::class)->getContacts();
            
        return $this->redirect('/login');
    }

    /**
     * @Route("/api/contacts/add", name="add_contact", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'];
        $email = $data['email'];
        $gender = $data['gender'];        
        $content = $data['content'];

        if (empty($name) || empty($gender) || empty($email) || empty($content)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->contactRepository->saveContact($name, $email, $gender, $content);

        return new JsonResponse(['status' => 1, 'message' => 'Contact successfully created!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/contacts/{id}", name="get_one_contact", methods={"GET"})
     */
    public function show($id): JsonResponse
    {
        $contact = $this->contactRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $contact->getId(),
            'name' => $contact->getName(),
            'email' => $contact->getEmail(),
            'gender' => $contact->getGender(),
            'content' => $contact->getContent(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/contacts", name="get_all_contacts", methods={"GET"})
     */
    public function list(): JsonResponse
    {
        $contacts = $this->contactRepository->findAll();
        $data = [];

        foreach ($contacts as $contact) {
            $data[] = [
                'id' => $contact->getId(),
                'name' => $contact->getName(),
                'email' => $contact->getEmail(),
                'gender' => $contact->getGender(),
                'content' => $contact->getContent(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/contacts/update", name="update_contact", methods={"POST"})
     */
    public function update(Request $request): JsonResponse
    {   

        $data = json_decode($request->getContent(), true);
        $contact = $this->contactRepository->findOneBy(['id' => $data['id']]);

        empty($data['name']) ? true : $contact->setName($data['name']);
        empty($data['email']) ? true : $contact->setEmail($data['email']);
        empty($data['gender']) ? true : $contact->setgender($data['gender']);
        empty($data['content']) ? true : $contact->setContent($data['content']);

        $updatedContact = $this->contactRepository->updateContact($contact);

        return new JsonResponse($updatedContact->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/api/contacts/remove/{id}", name="delete_contact", methods={"GET"})
     */
    public function delete($id): RedirectResponse
    {
        $contact = $this->contactRepository->findOneBy(['id' => $id]);

        $this->contactRepository->removeContact($contact);

        return $this->redirect('/contacts/list');
    }

}
