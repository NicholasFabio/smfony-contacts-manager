<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Contact;
use App\Repository\ContactRepository;

class ContactRenderController extends AbstractController
{

    public function add(): Response
    {   
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('contact/add.html.twig');
    }

    public function show(ManagerRegistry $doctrine, int $id): Response
    {    
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $contact = $doctrine->getRepository(Contact::class)->findContact($id);
        if (!$contact) {
            throw $this->createNotFoundException(
                'No contact found for id '.$id
            );
        }

        return $this->render('contact/show.html.twig', ['contact' => $contact[0]]);
    }

    public function delete(ManagerRegistry $doctrine, int $id){

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $entityManager = $doctrine->getManager();
        
        // check that there is a user for that id
        $contact = $doctrine->getRepository(Contact::class)->findContact($id);
        if (!$contact) {
            throw $this->createNotFoundException(
                'No contact found for id '.$id
            );
        }

        // Execute the delete using custom function in the repository
        $doctrine->getRepository(Contact::class)->removeContact($id);

        return $this->redirect('/contacts/list');
       
    }

    public function update(ManagerRegistry $doctrine, int $id): Response{
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $contact = $doctrine->getRepository(Contact::class)->findContact($id);

        if (!$contact) {
            throw $this->createNotFoundException(
                'No contact found for id '.$id
            );
        }

        $contact->setName('New product name!');
        $contact->setEmail('testing@newmail.com');
        $contact->setGender(2);
        $contact->setContent('New contact details!');

        $entityManager->flush();

        return $this->render('contact/show.html.twig', ['contact' => $contact[0]]);
    }

    public function list(ManagerRegistry $doctrine): Response
    {   
        
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $contacts = $doctrine->getRepository(Contact::class)->getContacts();

        return $this->render('contact/list.html.twig', ['contacts' => $contacts]);
    }

    public function createDummyContact(ManagerRegistry $doctrine, ValidatorInterface $validator): Response
    {   
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $entityManager = $doctrine->getManager();

        $contact = new Contact();
        $contact->setName('Test');
        $contact->setEmail('Test@gmail.com');
        $contact->setGender('1');
        $contact->setContent('Ergonomic and stylish!');

        $errors = $validator->validate($contact);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($contact);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        return $this->redirect('/contacts/list');
        
        //return new Response('Saved new contact with id '.$contact->getId());
    } 
}
