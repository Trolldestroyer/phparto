<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Entity\Report;
use AppBundle\Entity\Comentario;
use AppBundle\Form\ComentarioType;
use AppBundle\Form\ImageType;
use AppBundle\Form\ReportType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class IndexController extends Controller
{

    /**
     * @Route("/", name="app_index_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $m = $this->getDoctrine()->getManager();
        $repo=$m->getRepository('AppBundle:Report');
        $reports = $repo->findAll();
        return $this->render(':index:index.html.twig',
            [
                'reports'=> $reports,
            ]
        );
    }
    /**
     * @Route("/upload", name="app_index_upload")
     */
    public function uploadAction(Request $request)
    {
        $p = new Image();
        $form = $this->createForm(ImageType::class, $p);

        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $m = $this->getDoctrine()->getManager();
                $m->persist($p);
                $m->flush();

                return $this->redirectToRoute('app_index_index');
            }
        }
        return $this->render(':index:upload.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/nuevoComentario/{id}", name="app_index_nuevoComentario")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function nuevoComentarioAction($id, Request $request)
    {
        $c = new Comentario();
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(ComentarioType::class, $c);

        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $m = $this->getDoctrine()->getManager();
                $repo = $m->getRepository('AppBundle:Report');
                $report = $repo->find($id);
                $user = $this->get('security.token_storage')->getToken()->getUser();
                $c->setCreador($user);
                $c->setReport($report);
                $m->persist($c);
                $m->flush();

                return $this->redirectToRoute('app_index_show', ['slug' => $id]);
            }
        }
        return $this->render(':index:formcomentario.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/insertreport", name="app_index_insertreport")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function insertreportAction()
    {
        $p= new Report();
        $form = $this->createForm(ReportType::class, $p);
        return $this->render(':index:form.html.twig',
            [
                'form' =>   $form->createView(),
                'action'=>  $this->generateUrl('app_index_doinsertreport')
            ]
        );
    }
    /**
     * @Route("/doinsertreport", name="app_index_doinsertreport")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function doinsertreportAction(Request $request)
    {
        $p=new Report();
        //añadimos creator
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        // set creator in our object
        //is granted
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $p->setCreador($user);
        //create Form
        $form=$this->createForm(ReportType::class,$p);
        $form->handleRequest($request);
        if($form->isValid()) {
            $m = $this->getDoctrine()->getManager();
            $m->persist($p);
            $m->flush();
            $this->addFlash('messages', 'Report added');
            return $this->redirectToRoute('app_index_index');
        }
        $this->addFlash('messages','Review your form data');
        return $this->render(':index:form.html.twig',
            [
                'form'  =>  $form->createView(),
                'action'=>  $this->generateUrl('app_index_doinsertreport')
            ]
        );
    }

    /**
     * @Route("/removeComentario/{id}", name="app_index_removeComentario")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeComentarioAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Comentario');
        $comentario = $repo->find($id);
        $report = $comentario->getReport();
        $postid = $report->getID();
        $creator= $comentario->getCreador().$id;
        $current = $this->getUser().$id;

        if (($current!=$creator)&&(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }
        $m->remove($comentario);
        $m->flush();
        return $this->redirectToRoute('app_index_show',array('slug' => $postid));
    }
    /**
     * @Route("/remove/{id}", name="app_index_remove")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
            $m = $this->getDoctrine()->getManager();
            $repo = $m->getRepository('AppBundle:Report');
            $report = $repo->find($id);
            $creator= $report->getCreador().$id;
            $current = $this->getUser().$id;

        if (($current!=$creator)&&(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }
            $m->remove($report);
            $m->flush();
            return $this->redirectToRoute('app_index_index');
        }

    /**
     * @Route("/update/{id}", name="app_index_update")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

            $m=$this->getDoctrine()->getManager();
            $repo=$m->getRepository('AppBundle:Report');
            $report=$repo->find($id);

            $creator= $report->getCreador().$id;
            $current = $this->getUser().$id;
        if (($current!=$creator)&&(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }

        $form=$this->createForm(ReportType::class,$report);
            if($form->isValid()) {
                $m->flush();
                return $this->redirectToRoute('app_index_index');
            }
            return $this->render(':index:form.html.twig',
                [
                    'form'=>$form->createView(),
                    'action'=>$this->generateUrl('app_index_doUpdate',['id'=>$id])
                ]
            );


    }

    /**
     * @Route("/doUpdate/{id}", name="app_index_doUpdate")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function doUpdateAction($id,Request $request)
    {

        $m= $this->getDoctrine()->getManager();
        $repo= $m->getRepository('AppBundle:Report');
        $report= $repo->find($id);
        $form=$this->createForm(ReportType::class,$report);

        //El producto es actualizado con estos datos
        $form->handleRequest($request);
        $report->setUpdatedAt();

        if($form->isValid()){
            $m->flush();
            $this->addFlash('messages','Report Updated');

            return $this->redirectToRoute('app_index_index');
        }

        $this->addFlash('message' , 'Review your form');
        return $this->render(':index:form.html.twig',
            [
                'form'=> $form->createView(),
                'action'=> $this->generateUrl('app_index_doUpdate',['id'=>$id]),
            ]
        );
    }
    /**
     * @Route("/{slug}.html", name="app_index_show")
     */
    public function showAction($slug)
    {
        $m = $this->getDoctrine()->getManager();
        $repository= $m->getRepository('AppBundle:Report');
        $report=$repository->find($slug);
        return $this->render(':report:report.html.twig', [
            'report'   => $report,
        ]);
    }
    /**
     * @Route("/usuario/{slug}.html", name="app_usuario_show")
     *
     */
    public function showUserAction($slug)
    {
        $m = $this ->getDoctrine()->getManager();
        $repository= $m->getRepository('UserBundle:User');
        $usuario=$repository->find($slug);
        return $this->render('usuario/usuario.html.twig',[
            'usuario' => $usuario,
        ]);
    }


}
