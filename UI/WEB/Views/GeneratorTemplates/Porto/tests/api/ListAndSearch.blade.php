<?= "<?php\n" ?>

namespace {{ $gen->entityName() }};

use {{ $gen->containerName() }}\ApiTester;
use {{ $gen->entityModelNamespace() }};

/**
 * ListAndSearch{{ $gen->entityName() }}Cest Class.
 * 
 * @author [name] <[<email address>]>
 */
class ListAndSearch{{ $gen->entityName(true) }}Cest
{
    /**
     * @var string
     */
	private $endpoint = 'v1/{{ str_slug($gen->tableName, $separator = "-") }}';

    /**
     * @var App\Containers\User\Models\User
     */
    private $user;

    public function _before(ApiTester $I)
    {
    	$this->user = $I->loginAdminUser();
        $I->initData();
        $I->haveHttpHeader('Accept', 'application/json');
    }

    public function _after(ApiTester $I)
    {
    }

    public function listAndSearch{{ $gen->entityName() }}(ApiTester $I)
    {
    	$data = factory({{ $gen->entityName() }}::class, 10)->create();

        $I->sendGET($this->endpoint);

        $I->seeResponseCodeIs(200);
        $response = json_decode($I->grabResponse());
        $I->assertCount(10, $response->data);
    }
}