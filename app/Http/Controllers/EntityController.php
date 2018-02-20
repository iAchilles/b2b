<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use App\Interfaces\EntityServiceInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * EntityController class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class EntityController extends Controller
{

    /**
     * @var EntityServiceInterface
     */
    protected $entityService;

    protected $request;


    /**
     * EntityController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request  = $request;
    }


    /**
     * Optional fields:
     * - id
     * - criteria
     * - order
     * - limit
     * - offset
     * - count
     * - related
     */
    public function list()
    {
        $id       = $this->request->input('id', null);
        $related  = $this->request->input('related', null);
        $criteria = $this->request->input('criteria', []);
        $order    = $this->request->input('order', null);
        $limit    = $this->request->input('limit', 50);
        $offset   = $this->request->input('offset', 0);
        $count    = $this->request->input('count', false);

        return $this->entityService->find($id, $related, $criteria, $order, $limit, $offset, $count);
    }


    /**
     * Required fields:
     * - fields
     * @return array
     */
    public function create()
    {
        if (empty($this->request->input())) {
            throw new BadRequestHttpException();
        }
        return $this->entityService->create($this->request->input());
    }


    /**
     * Required fields:
     * - fields
     * - criteria
     * @return array
     */
    public function update($id = null)
    {
        if (empty($this->request->input())) {
            throw new BadRequestHttpException();
        }
        if (!$this->request->input('criteria') || !is_array($this->request->input('criteria'))) {
            throw new BadRequestHttpException();
        }

        return $this->entityService->update($this->request->input('fields'), $this->request->input('criteria'));
    }


    /**
     * Required fields:
     * - criteria
     * @return bool
     */
    public function delete($id = null)
    {
        if (!$this->request->input('criteria') || !is_array($this->request->input('criteria'))) {
            throw new BadRequestHttpException();
        }

        return $this->entityService->delete($this->request->input('criteria'));
    }
}
