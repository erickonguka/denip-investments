<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Proposal;

class DocumentVerificationController extends Controller
{
    public function verify($hash)
    {
        $document = null;
        $type = null;
        
        // Check with new format (type_id)
        $types = [
            'invoice' => ['model' => Invoice::class, 'name' => 'Invoice'],
            'quotation' => ['model' => Quotation::class, 'name' => 'Quotation'],
            'proposal' => ['model' => Proposal::class, 'name' => 'Proposal'],
            'project' => ['model' => \App\Models\Project::class, 'name' => 'Project']
        ];
        
        foreach ($types as $typeKey => $typeInfo) {
            $documents = $typeInfo['model']::all();
            foreach ($documents as $doc) {
                if (md5($typeKey . '_' . $doc->id) === $hash) {
                    $document = $doc;
                    $type = $typeInfo['name'];
                    break 2;
                }
            }
        }
        
        // Fallback to old format for backward compatibility
        if (!$document) {
            foreach ($types as $typeKey => $typeInfo) {
                $documents = $typeInfo['model']::all();
                foreach ($documents as $doc) {
                    if (md5($doc->id) === $hash) {
                        $document = $doc;
                        $type = $typeInfo['name'];
                        break 2;
                    }
                }
            }
        }
        
        return view('verify-document', compact('document', 'type', 'hash'));
    }
}