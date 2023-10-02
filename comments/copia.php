use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacationsTable extends Migration
{
/**
* Run the migrations.
*
* @return void
*/
public function up()
{
Schema::create('vacations', function (Blueprint $table) {
$table->id();
$table->unsignedBigInteger('user_id');
$table->enum('user_type', ['employee', 'manager']);
$table->date('start_date');
$table->date('end_date');
$table->string('reason');
$table->string('status');
$table->timestamp('created_at')->nullable();
$table->timestamp('approved_at')->nullable();
$table->timestamp('rejected_at')->nullable();
$table->unsignedBigInteger('manager_id')->nullable();
$table->text('manager_comment')->nullable();

// Foreign key constraint for user_id
$table->foreign('user_id')
->references('id')
->on('users')
->onDelete('cascade');

// Foreign key constraint for manager_id
$table->foreign('manager_id')
->references('id')
->on('users')
->onDelete('set null');
});
}

/**
* Reverse the migrations.
*
* @return void
*/
public function down()
{
Schema::dropIfExists('vacations');
}
}
