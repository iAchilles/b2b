<?php
namespace app\Http\Controllers;

use App\Services\Image\Uploader;
use App\Services\User\SigninService;
use App\Services\User\SignupService;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;


/**
 * ApiController class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class ApiController extends BaseController
{

    /**
     * @var Request
     */
    private $request;


    /**
     * ApiController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    /**
     * POST /api/signin
     * Required fields:
     * - username
     * - password
     *
     * @return array
     */
    public function signin()
    {
        $this->validate($this->request, [ 'username' => 'required', 'password' => 'required' ]);

        return SigninService::signin($this->request->input());
    }


    /**
     * POST /api/signup
     * Required fields:
     * - username
     * - password
     *
     * @return array
     */
    public function signup()
    {
        $this->validate($this->request, [ 'username' => 'required|unique:App\Entities\User,username', 'password' => 'required' ]);

        return SignupService::signup($this->request->input());
    }


    /**
     * POST /api/images
     * Required fields:
     * - image (Base64-encoded string representation)
     */
    public function upload()
    {
        return (new Uploader($this->request->input('image'), $this->request->get('token')->getClaim('uid')))->handle();
    }
}
