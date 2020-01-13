<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;



class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function index()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/article/create", name="article_create")
     */
    public function create(Request $request){
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirectToRoute('article');
        }
        
        return $this->render('article/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

        /**
     * @Route("/article/{id}", name="article_show")
     */
    public function show($id)
    {
        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article found'
            );
        }

        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }

}
