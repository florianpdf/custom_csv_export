<?php

namespace YourBundleName\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportCsvController extends Controller
{
    /**
     * List all entities of application
     *
     */
    public function showTablesAction(){
        $em = $this->getDoctrine()->getManager();
        $meta = $em->getMetadataFactory()->getAllMetadata();
        $entities = array();
        foreach ($meta as $m) {
            $entities[$m->getName()] = preg_replace('/.*\\\\/', '', $m->getName());
        }

        return $this->render('@PlateForme/showTables.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * List all fields for a selected entity
     *
     */
    public function getTableFieldsAction(){
        $entity = $_POST['table'];
        $session = $this->get('session');
        $session->set('linkEntity', $entity);
        $em = $this->getDoctrine()->getManager();
        $columnEntity = $em->getClassMetadata($entity)->getFieldNames();

        return $this->render('@PlateForme/showTables.html.twig', array(
            'columns' => $columnEntity
        ));
    }

    /**
     * Export select to CSV.
     *
     */
    public function generateCsv2Action() {
        $session = $this->get('session');

        $fieldsEntity = $_POST['fields'];
        $linkEntity = $session->get('linkEntity');

        $response= new StreamedResponse();
        $csvContent = $this->get('csvExport')->getFields($linkEntity, $fieldsEntity);

        $response->setCallback(function() use ($csvContent, $fieldsEntity) {
            $handle = fopen('php://output','w+');
            fputcsv($handle, $fieldsEntity,';');

            foreach ($csvContent as $content) {
                $fieldCsv = array();
                foreach ($fieldsEntity as $field){
                    $fieldCsv[] = $content[$field];
                }
                fputcsv($handle, $fieldCsv,';');
            }
            fclose($handle);
        });
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition','attachment; filename="exportCsv.csv"');
        return $response;
    }
}
