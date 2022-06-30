<?php

namespace App\Controller;

use App\Factory\SecretFactory;
use App\Repository\SecretRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/secrets", name: "secrets_")]
class SecretController extends AbstractRespondController
{
    public function __construct(private readonly SecretRepository $secretRepository)
    {
    }

    #[Route(path: "", name: "all", methods: ["GET"])]
    function all(Request $request): Response
    {
		$headerAccept = $request->headers->get("accept");

        $data = $this->secretRepository->findAllActiveSecrets();

		return $this->respond($headerAccept, $data);
    }

    #[Route(path: "/{hash}", name: "byHash", methods: ["GET"])]
    function getByHash(string $hash, Request $request) : Response
    {
		$headerAccept = $request->headers->get("accept");

        $data = $this->secretRepository->findOneActiveSecretByHash($hash);

        if ($data) {
			return $this->respond($headerAccept, $data);
        } else {
            return $this->respond($headerAccept, ["error" => "Secret with hash " . $hash . " was not found!"], 404);
        }
    }

	#[Route(path: "", name: "createSecret", methods: ["POST"])]
	function createSecret(Request $request): Response
	{
		$headerAccept = $request->headers->get("accept");

		$secret = $request->query->get("secret");
		$expiresAfterViews = $request->query->get("expireAfterViews");
		$expiresAfter = $request->query->get("expireAfter");

		if (!isset($secret) || !isset($expiresAfterViews) || !isset($expiresAfter)) {
			if (empty($secret) || empty($expiresAfterViews) || empty($expiresAfter)) {
				return $this->respond($headerAccept, ["error" => "Invalid input!"], 405);
			}
		}

		$entity = SecretFactory::createSecret($secret, $expiresAfterViews, $expiresAfter);
		$this->secretRepository->add($entity, true);

		return $this->respond($headerAccept, $entity->asAssocArray());
	}
}
