<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageNotFoundController extends AbstractRespondController
{
    public function index(Request $request): Response
    {
        $headerAccept = $request->headers->get("accept");

        return $this->respond($headerAccept, ["error" => "No content on this route"], 404);
    }
}
