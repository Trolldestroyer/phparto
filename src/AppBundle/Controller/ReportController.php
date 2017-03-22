<?php
/**
 * Created by PhpStorm.
 * User: albertau
 * Date: 28/02/17
 * Time: 19:15
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Report;
use AppBundle\Form\ReportType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReportController extends Controller
{
    /**
     * @Route (" / ", name="app_report_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $m= $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Report');
        /*return $this->render(':report:index.html.twig', ['report'=> $report]);*/
        $report = $repo->findAll();
        return $this->render(':report:index.html.twig', ['report'=>$report]);
    }


    /**
     * updateAction
     *
     * @Route(
     *     path="/update/{id}",
     *     name="app_report_update"
     * )
     *
     */
    public function updateAction($id){
        $m = $this->getDoctrine()->getManager();
        $repository = $m->getRepository('AppBundle:Report');
        $report = $repository->find($id);
        $form = $this->createForm(ReportType::class, $report);
        return $this->render(':report:form.html.twig',
            [
                'form'      => $form->createView(),
                'action'  => $this->generateUrl('app_report_doUpdate',['id'=>$id])
            ]
        );
    }


    /**
     * doUpdateAction
     *
     * @Route(
     *     path="/do-update/{id}",
     *     name="app_report_doUpdate"
     * )
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public  function doUpdateAction($id, Request $request){

        $m          = $this->getDoctrine()->getManager();
        $repository = $m->getRepository('AppBundle:Report');
        $report   = $repository->find($id);
        $form    = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);

        if ($form->isValid()){
            $m->flush();
            $this->addFlash('messages', 'report actualizado');

            return $this->redirectToRoute('app_report_index');
        }

        $this->addFlash('messages', 'Review you form');

        return $this->render(':report:form.html.twig',
            [
                'form'      => $form->createView(),
                'action'  => $this->generateUrl('app_report_doUpdate',['id'=>$id])
            ]

        );
    }
    /**
     * @Route(
     *     path="/insert",
     *     name="app_report_insert"
     * )
     */
    public function insertAction()
    {
        $report = new Report();
        $form = $this->createForm(ReportType::class, $report);

        return $this->render(':report:form.html.twig',
            [
                'form' => $form->CreateView(),
                'action'    => $this->generateUrl('app_report_doInsert')
            ]
        );
    }
    /**
     * @Route(
     *     path="/do-insert",
     *     name="app_report_doInsert"
     * )
     */
    public function doInsertAction(Request $request)
    {
        $report = new Report();
        $form = $this->createForm(ReportType::class, $report);

        $form->handleRequest($request);

        if($form->isValid()){
            $m = $this->getDoctrine()->getManager();
            $m->persist($report);
            $m->flush();

            $this->addFlash('messages', 'Report añadido');

            return $this->redirectToRoute('app_report_index');
        }

        $this->addFlash('messages', 'Review your form data ss');

        return $this->render(':report:form.html.twig',
            [
                'form' => $form->CreateView(),
                'action'    => $this->generateUrl('app_report_doInsert')

            ]);

    }
    /**
     * @Route(
     *     path="/remove/{id}",
     *     name="app_report_remove"
     * )
     */
    public function removeAction($id)
    {
        $m = $this->getDoctrine()->getManager();
        $repository = $m->getRepository('AppBundle:Report');
        $user = $repository->find($id);
        $m->remove($user);
        $m->flush();
        $this->addFlash('messages', 'User has beeeen removed');
        return $this->redirectToRoute('app_report_index');
    }







}