<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class MeetingType extends Enum
{
    const Email = 0;
    const Discussion = 1;
    const Event = 2;

    /**
     * Display the specified resource.
     *
     * @return String
     */
    public function getFullDescription() {
        if ($this->value === self::Discussion) {
            return 'Discussion (a one-on-one or small-group meeting or call)';
        }
        if ($this->value === self::Event) {
            return 'Event (a conference, webinar, or other event with many participants)';
        }

        return parent::getDescription($this->value);
    }
}