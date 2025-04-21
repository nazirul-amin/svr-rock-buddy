<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use App\Models\Submission;
use Illuminate\Support\Facades\Storage;
use Prism\Prism\Prism;
use Prism\Prism\Enums\Provider;
use Prism\Prism\ValueObjects\Messages\Support\Image;
use Prism\Prism\ValueObjects\Messages\UserMessage;

trait AiSuggestImprovement
{
    /**
     * Generate fun and casual improvement suggestions for a participant.
     *
     * @param  Collection|Submission[]  $submissions
     * @return string
     */
    public function suggestImprovement(Collection $submissions): string
    {
        $messages = [];

        foreach ($submissions as $submission) {
            $theme = $submission->theme->name ?? 'a mystery theme';
            $score = $submission->score;
            $path  = $submission->path;
            $image = Image::fromPath(Storage::disk('public')->path($path));

            $content = sprintf(
                "Alrighty, here's a submission with the theme '%s' and it scored %d points (from six judges, each giving 0–15). Have a look at the attached masterpiece and let us know what you think! Feel free to be cheeky, creative, and constructive.",
                $theme,
                $score
            );

            $messages[] = new UserMessage($content, [$image]);
        }

        $systemPrompt = "You're a super enthusiastic judge in a wildly fun pet rock decorating competition. You've seen it all—googly eyes, glitter overload, and pom pom perfection. Review the submissions and give playful, encouraging feedback with suggestions to level up the creativity, style, and theme vibes.";

        $response = Prism::text()
            ->using(Provider::Gemini, 'gemini-2.0-flash')
            ->withClientOptions(['timeout' => 60])
            ->withSystemPrompt($systemPrompt)
            ->withMessages($messages)
            ->asText();

        return trim($response->text);
    }
}
