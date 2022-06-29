<?php

namespace App\Controller;

use App\Repository\SecretRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route(path: "/secrets", name: "secrets_")]
class SecretController extends AbstractController
{
    public function __construct(private readonly SecretRepository $secrets)
    {
    }

    #[Route(path: "", name: "all", methods: ["GET"])]
    function all(Request $request): Response
    {
        $data = $this->secrets->findAll();

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $xmlContent = $serializer->serialize($data, "xml");

//        var_dump($xmlContent);

//        return new XmlResponse($xmlContent);
        return $this->json($data, 200, ["Content-Type" => "application/json"]);
    }

    #[Route(path: "/{id}", name: "byId", methods: ["GET"])]
    function getById(int $id) : Response
    {
        $data = $this->secrets->findOneBy(["id" => $id]);
        if ($data) {
            return $this->json($data);
        } else {
            return $this->json(["error" => "Secret with id " . $id . " was not found!"]);
        }
    }
}
