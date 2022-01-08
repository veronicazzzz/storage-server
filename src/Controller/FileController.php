<?php

namespace App\Controller;

use App\Entity\File;
use App\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Mime\FileinfoMimeTypeGuesser;
use Symfony\Component\Serializer\SerializerInterface;

class FileController extends AbstractController
{
    /**
     * @var FileService
     */
    private $fileService;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(FileService $fileService, SerializerInterface $serializer)
    {
        $this->fileService = $fileService;
        $this->serializer = $serializer;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request): JsonResponse
    {
        try {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $request->files->get('file');
            if ($uploadedFile) {
                $file = new File();

                $fileName = $this->fileService->upload($uploadedFile);
                $fileSize = $uploadedFile->getSize();

                $file
                    ->setFileName($fileName)
                    ->setFileSize($fileSize)
                ;

                $em = $this->getDoctrine()->getManager();
                $em->persist($file);
                $em->flush();

                return new JsonResponse(
                    [
                        'code'    => '201',
                        'message' => 'File created',
                        'file'    => [
                            'fileName' => $fileName,
                            'fileSize' => $fileSize,
                        ]
                    ],
                    Response::HTTP_CREATED
                );
            }

            throw new FileException();
        } catch (FileException $e) {
            return new JsonResponse(
                [
                    'code'    => '400',
                    'message' => $e->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function removeAction(Request $request): JsonResponse
    {
        $fileName = $request->get('filename');

        $fileRepository = $this->getDoctrine()->getRepository(File::class);
        $em             = $this->getDoctrine()->getManager();

        /** @var File $file */
        $file = $fileRepository->findOneBy([
            'fileName' => $fileName
        ]);

        if ($file) {
            $this->fileService->remove($file->getFileName());

            $em->remove($file);
            $em->flush();

            return new JsonResponse(
                [
                    'code'    => '204',
                    'message' => "File $fileName removed"
                ],
                204
            );
        }

        return new JsonResponse(
            [
                'code'    => '404',
                'message' => "File $fileName not found"
            ],
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * @return JsonResponse
     */
    public function getAllInfoAction(): JsonResponse
    {
        $fileRepository = $this->getDoctrine()->getRepository(File::class);

        $files = $fileRepository->findAll();

        $response = json_decode($this->serializer->serialize($files, 'json'), true);
        return new JsonResponse($response, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     *
     * @return BinaryFileResponse
     */
    public function getFileAction(Request $request): BinaryFileResponse
    {
        $fileName        = $request->get('filename');
        $targetDirectory = $this->fileService->getTargetDirectory();

        $fileFullPath = $targetDirectory . '/' . $fileName;

        $response        = new BinaryFileResponse($fileFullPath);
        $mimeTypeGuesser = new FileinfoMimeTypeGuesser();

        if ($mimeTypeGuesser->isGuesserSupported()) {
            $response->headers->set('Content-Type', $mimeTypeGuesser->guessMimeType($fileFullPath));
        } else {
            $response->headers->set('Content-Type', 'text/plain');
        }

        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $fileName
        );

        return $response;
    }
}