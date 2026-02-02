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

        /* ================= ATTRIBUTES ================= */
        $xml->addAttribute('id', $compaign->id);
        $xml->addAttribute('name', $compaign->name);
        $xml->addAttribute('status', $compaign->status);

        /* ================= BASE INFO ================= */
        $xml->addChild('budget', $compaign->budget);
        $xml->addChild('ad_duration', $compaign->ad_duration);
        $xml->addChild('note', $compaign->note);

        /* ================= DATES ================= */
        $dates = $xml->addChild('dates');
        $dates->addChild('start', $compaign->start_date);
        $dates->addChild('end', $compaign->end_date);

        /* ================= BELONGS TO ================= */
        self::addNode($xml, 'objective', $compaign->compaignObjective);
        self::addNode($xml, 'category', $compaign->compaignCategory);
        self::addNode($xml, 'langue', $compaign->langue);
        self::addNode($xml, 'gender', $compaign->gender);
        self::addNode($xml, 'template_slot', $compaign->templateSlot);
        self::addNode($xml, 'user', $compaign->user);

        /* ================= FILTERS ================= */
        $filters = $xml->addChild('filters');

        self::addCollection($filters, 'cinema_chains', 'cinema_chain', $compaign->cinemaChains);
        self::addCollection($filters, 'locations', 'location', $compaign->locations);
        self::addCollection($filters, 'movie_genres', 'genre', $compaign->movieGenres);
        self::addCollection($filters, 'hall_types', 'hall', $compaign->hallTypes);
        self::addCollection($filters, 'target_types', 'target', $compaign->targetTypes);
        self::addCollection($filters, 'interests', 'interest', $compaign->interests);

        /* ================= BRANDS ================= */
        self::addCollection($xml, 'brands', 'brand', $compaign->brands);

        /* ================= MOVIES ================= */
        self::addCollection($xml, 'movies', 'movie', $compaign->movies);

        /* ================= SLOTS + DCP ================= */
        if ($compaign->slots->isNotEmpty()) {
            $slotsNode = $xml->addChild('slots');

            foreach ($compaign->slots as $slot) {
                $slotNode = $slotsNode->addChild('slot');
                $slotNode->addAttribute('id', $slot->id);
                $slotNode->addAttribute('name', $slot->name);

                foreach ($compaign->dcpCreatives->where('pivot.slot_id', $slot->id) as $dcp) {
                    $dcpNode = $slotNode->addChild('dcp');
                    $dcpNode->addAttribute('id', $dcp->id);
                    $dcpNode->addAttribute('name', $dcp->name);
                    $dcpNode->addAttribute('position', $dcp->pivot->position);
                    $dcpNode->addAttribute('duration', $dcp->pivot->duration ?? 0);
                }
            }
        }

        /* ================= INVOICES ================= */
        if ($compaign->invoices->isNotEmpty()) {
            $invoicesNode = $xml->addChild('invoices');

            foreach ($compaign->invoices as $invoice) {
                $invoiceNode = $invoicesNode->addChild('invoice');
                $invoiceNode->addAttribute('id', $invoice->id);
                $invoiceNode->addChild('amount', $invoice->amount);
                $invoiceNode->addChild('status', $invoice->status);
                $invoiceNode->addChild('created_at', $invoice->created_at);
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
        $node->addAttribute('name', $model->name ?? '');
    }

    private static function addCollection($parent, string $wrapper, string $nodeName, $collection)
    {
        if (!$collection || $collection->isEmpty()) return;

        $wrapperNode = $parent->addChild($wrapper);

        foreach ($collection as $item) {
            $node = $wrapperNode->addChild($nodeName);
            $node->addAttribute('id', $item->id);
            $node->addAttribute('name', $item->name ?? '');
        }
    }

}
