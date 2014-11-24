<?php

namespace Melodia\FileBundle\Controller\Api;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Engage360d\Bundle\RestBundle\Controller\RestController;
use JMS\Serializer\SerializationContext;
use Melodia\FileBundle\Entity\File;
use Melodia\FileBundle\Form\Type\FileFormType;

class FileController extends RestController
{
    /**
     * @ApiDoc(
     *  section="File",
     *  description="Получение всех файлов.",
     *  filters={
     *      {
     *          "name"="page",
     *          "dataType"="integer",
     *          "default"=1,
     *          "required"=false
     *      },
     *      {
     *          "name"="limit",
     *          "dataType"="integer",
     *          "default"=20,
     *          "required"=false
     *      }
     *  }
     * )
     */
    public function getFilesAction(Request $request)
    {
        $page = $request->query->get('File') ?: 1;
        $limit = $request->query->get('limit') ?: 20;

        // Check filters' format
        if (!is_numeric($page) || !is_numeric($limit)) {
            return new JsonResponse(null, 400);
        }

        $files = $this->getDoctrine()->getRepository(File::REPOSITORY)
            ->findSubset($page, $limit);
        $files = $this->get('jms_serializer')->serialize($files, 'json',
            SerializationContext::create()->setGroups(array("getAllFiles"))
        );

        return new Response($files, 200);
    }

    /**
     * @ApiDoc(
     *  section="File",
     *  description="Получение детальной информации об одном файле.",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+"
     *      }
     *  }
     * )
     */
    public function getFileAction($id)
    {
        $file = $this->getDoctrine()->getRepository(File::REPOSITORY)
            ->findOneBy(array('id' => $id));

        if (!$file) {
            return new JsonResponse(null, 404);
        }

        $file = $this->get('jms_serializer')->serialize($file, 'json',
            SerializationContext::create()->setGroups(array("getOneFile"))
        );

        return new Response($file, 200);
    }

    /**
     * @ApiDoc(
     *  section="File",
     *  description="Загрузка файла.",
     *  input="Melodia\FileBundle\Form\Type\FileFormType",
     *  output={
     *      "class"="Melodia\FileBundle\Entity\File",
     *      "groups"={"postFile"}
     *  }
     * )
     */
    public function postFileAction(Request $request)
    {
        $file = new File();

        $form = $this->createForm(new FileFormType(), $file);
        $form->submit($request->files->all());

        if (!$form->isValid()) {
            return new JsonResponse($this->getErrorMessages($form), 400);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($file);
        $entityManager->flush();

        $file = $this->get('jms_serializer')->serialize($file, 'json',
            SerializationContext::create()->setGroups(array("postFile"))
        );

        return new Response($file, 201);
    }

    /**
     * @ApiDoc(
     *  section="File",
     *  description="Удаление файла.",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+"
     *      }
     *  }
     * )
     */
    public function deleteFileAction($id)
    {
        $file = $this->getDoctrine()->getRepository(File::REPOSITORY)
            ->findOneBy(array('id' => $id));

        if (!$file) {
            return new JsonResponse(null, 404);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($file);
        $entityManager->flush();

        return new JsonResponse(null, 200);
    }
}
