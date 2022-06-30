<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AbstractRespondController extends AbstractController
{

    protected function respond($headerAccept, $returnData, $status = 200, $header = []): Response
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $responseContent = "";

        if ($headerAccept == "text/xml" || $headerAccept == "application/xml") {
            $responseContent = $serializer->serialize($returnData, "xml");
            $header = array_merge($header, ["Content-Type" => "text/xml"]);
        } else if ($headerAccept == "yaml") {
            //yaml response
        } else {
            $responseContent = $serializer->serialize($returnData, "json");
            $header = array_merge($header, ["Content-Type" => "application/json"]);
        }

        return new Response($responseContent, $status, $header);
    }
}
