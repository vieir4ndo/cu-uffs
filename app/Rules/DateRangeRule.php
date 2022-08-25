<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class DateRangeRule implements Rule
{
    private Carbon $initDate;
    private Carbon $finalDate;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($init_date, $finalDate)
    {
        $this->initDate = Carbon::parse($init_date);
        $this->finalDate = Carbon::parse($finalDate);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->finalDate->diffInDays($this->initDate) < 120;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Datas informadas excedem o limite de 120 dias.';
    }
}
