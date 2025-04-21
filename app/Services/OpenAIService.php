<?php

namespace App\Services;

use Illuminate\Support\Collection;
use App\Models\Submission;
use Illuminate\Support\Facades\Storage;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Prism;
use Prism\Prism\ValueObjects\Messages\Support\Image;
use Prism\Prism\ValueObjects\Messages\UserMessage;

class OpenAIService
{
    /**
     * Generate improvement suggestions for a participant.
     *
     * @param  Collection|Submission[]  $submissions
     * @return string
     */
    public function suggestImprovement(Collection $submissions): string
    {
        $messages = [];
        $basePath = config('app.url');

        foreach ($submissions as $submission) {
            $theme = $submission->theme->name ?? 'Unknown';
            $score = $submission->score;
            $path  = $submission->path;
            $image = Image::fromPath(Storage::disk('public')->path($path));

            $content = sprintf(
                "The theme for this submission is %s and the total Score of %d which is an aggregate of six judges, each scoring 10–15 points. Attached are the submission",
                $theme,
                $score
            );

            $messages[] = new UserMessage($content, [$image]);
        }

        $systemPrompt = "You are a seasoned judge in a pet rock decorating competition with over 10 years of experiences. Analyse each submission, evaluate styling, creativity, theme alignment and the awarded score";

        $response = Prism::text()
            ->using(Provider::Gemini, 'gemini-2.0-flash')
            ->withClientOptions(['timeout' => 60])
            ->withSystemPrompt($systemPrompt)
            ->withMessages($messages)
            ->asText();

        return trim($response->text);
    }
}
