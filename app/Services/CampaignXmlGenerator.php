<?php

namespace App\Services;

use App\Models\Compaign;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;

class CampaignXmlGenerator
{
    public static function generate(Compaign $compaign): string
    {
        $xml = new SimpleXMLElement('<campaign/>');
        $xml->addAttribute('id', $compaign->id);
        $xml->addAttribute('name', $compaign->name);

        $xml->addChild('budget', $compaign->budget);

        /* ================= DATES ================= */
        $dates = $xml->addChild('dates');
        $dates->addChild('start', $compaign->start_date);
        $dates->addChild('end', $compaign->end_date);

        /* ================= BASE ================= */
        self::addNode($xml, 'langue', $compaign->langue);
        self::addNode($xml, 'gender', $compaign->gender);

        /* ================= FILTERS ================= */
        $filters = $xml->addChild('filters');

        if ($compaign->cinemaChain) {
            self::addNode($filters, 'cinema_chain', $compaign->cinemaChain);
        }

        self::addCollection($filters, 'locations', 'location', $compaign->locations);
        self::addCollection($filters, 'movie_genres', 'genre', $compaign->movieGenres);
        self::addCollection($filters, 'hall_types', 'hall', $compaign->hallTypes);
        self::addCollection($filters, 'targets', 'target', $compaign->targetTypes);
        self::addCollection($filters, 'interests', 'interest', $compaign->interests);

        /* ================= MOVIES ================= */
        self::addCollection($xml, 'movies', 'movie', $compaign->movies);

        /* ================= SLOTS + DCP ================= */
        $slotsNode = $xml->addChild('slots');

        foreach ($compaign->slots as $slot) {

            $slotNode = $slotsNode->addChild('slot');
            $slotNode->addAttribute('id', $slot->id);
            $slotNode->addAttribute('name', $slot->name);

            foreach ($compaign->dcpCreatives as $dcp) {
                if ($dcp->pivot->slot_id == $slot->id) {

                    $dcpNode = $slotNode->addChild('dcp');
                    $dcpNode->addAttribute('id', $dcp->id);
                    $dcpNode->addAttribute('name', $dcp->name);
                    $dcpNode->addAttribute('duration', $dcp->pivot->duration ?? 0);
                }
            }
        }

        /* ================= SAVE ================= */
        $path = "campaigns/campaign_{$compaign->id}.xml";
        Storage::disk('public')->put($path, $xml->asXML());

        return $path;
    }

    /* ================= HELPERS ================= */

    private static function addNode($parent, string $name, $model = null)
    {
        if (!$model) return;

        $node = $parent->addChild($name);
        $node->addAttribute('id', $model->id);
        $node->addAttribute('name', $model->name);
    }

    private static function addCollection($parent, string $wrapper, string $nodeName, $collection)
    {
        if ($collection->isEmpty()) return;

        $wrapperNode = $parent->addChild($wrapper);

        foreach ($collection as $item) {
            $node = $wrapperNode->addChild($nodeName);
            $node->addAttribute('id', $item->id);
            $node->addAttribute('name', $item->name);
        }
    }
}
