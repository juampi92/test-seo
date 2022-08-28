<?php

namespace Juampi92\TestSEO\SnapshotFormatters;

use Juampi92\TestSEO\SEOData;

interface SnapshotSerializer
{
    public function toArray(SEOData $data): array;
}
