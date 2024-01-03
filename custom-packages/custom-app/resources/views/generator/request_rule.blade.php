@php echo "<?php"
@endphp

namespace {{$requestNamespace}};

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class {{$requestClassName}} extends FormRequest
{
    /**
    * Determine if the user is authorized to make this request.
    *
    * {{'@'}}return bool
    */
    public function authorize()
    {
        return Gate::allows("{{$permissionName}}");
    }

    /**
    * Get the validation rules that apply to the request.
    *
    * {{'@'}}return array
    */
    public function rules()
    {
        return [
    @foreach($rules as $ruleName => $rule)
        {!! "'$ruleName'" !!} => [@foreach($rule as $item){{!$loop->first?",":"" }}{!! "'$item'" !!}@endforeach],
    @endforeach
    ];
    }
}
