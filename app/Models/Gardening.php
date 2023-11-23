<?php
namespace App\Models;

use App\Models\MyModel;

/**
 * Модель для садоводсва
 *
 * @property int $id
 * @property string $name
 * @property string $full_name
 * @property string|null $PersonalAcc
 * @property string|null $BankName
 * @property string|null $BIC
 * @property string|null $CorrespAcc
 * @property string|null $PayeeINN
 * @property string|null $descriptions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\FileModel> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message\MessageModel> $message
 * @property-read int|null $message_count
 * @method static \Illuminate\Database\Eloquent\Builder|Gardening newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gardening newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gardening query()
 * @method static \Illuminate\Database\Eloquent\Builder|Gardening whereBIC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gardening whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gardening whereCorrespAcc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gardening whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gardening whereDescriptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gardening whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gardening whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gardening whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gardening wherePayeeINN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gardening wherePersonalAcc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gardening whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $log
 * @mixin \Eloquent
 */
class Gardening extends MyModel
{
    protected $fillable = ['BankName', 'BIC', 'CorrespAcc', 'full_name', 'name', 'PayeeINN', 'PersonalAcc'];
    //
}
