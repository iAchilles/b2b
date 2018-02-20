<?php
namespace App\Http\Controllers;

use App\Entities\Recipe;
use App\Services\DB\RecipeService;
use Illuminate\Http\Request;
use Lcobucci\JWT\Token;

/**
 * RecipeController class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class RecipeController extends EntityController
{

    /**
     * @var Token
     */
    private $token;


    /**
     * RecipeController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->entityService = new RecipeService(Recipe::class);
        $this->token         = $request->get('token');
        parent::__construct($request);
    }


    /**
     * POST /api/recipes/
     * Required fields:
     * - title
     * - description
     * - fileId
     *
     * @return array
     */
    public function create()
    {
        $this->validate($this->request, [ 'title' => 'required', 'description' => 'required', 'fileId' => 'required' ]);
        $this->request->merge([ 'user' => $this->token->getClaim('uid') ]);
        return parent::create();
    }


    /**
     * PATCH /api/recipes/{id}
     *
     * @param string $id Recipe identifier.
     * @return array
     */
    public function update($id = null)
    {
        $criteria = [ 'user' => $this->token->getClaim('uid'), 'id' => $id ];
        $this->request->merge([ 'criteria' => $criteria, 'fields' => $this->request->input() ]);
        return parent::update($id);
    }


    /**
     * DELETE /api/recipes/{id}
     *
     * @param string $id Recipe identifier
     * @return bool
     */
    public function delete($id = null)
    {
        $criteria = [ 'user' => $this->token->getClaim('uid'), 'id' => $id ];
        $this->request->merge([ 'criteria' => $criteria ]);
        return parent::delete($id);
    }
}
