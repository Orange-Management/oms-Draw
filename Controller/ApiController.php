<?php

/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules\Draw
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */

declare(strict_types=1);

namespace Modules\Draw\Controller;

use Modules\Draw\Models\DrawImage;
use Modules\Draw\Models\DrawImageMapper;
use Modules\Media\Controller\ApiController as MediaController;
use Modules\Media\Models\UploadStatus;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\NotificationLevel;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Model\Message\FormValidation;
use phpOMS\System\File\Local\File;
use phpOMS\Utils\ImageUtils;

/**
 * Calendar controller class.
 *
 * @package Modules\Draw
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class ApiController extends Controller
{
    /**
     * Validate draw create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateDrawCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['title'] = empty($request->getData('title')))
            || ($val['image'] = empty($request->getData('image')))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiDrawCreate(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        if (!empty($val = $this->validateDrawCreate($request))) {
            $response->set('draw_create', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $path      = MediaController::createMediaPath();
        $extension = 'png';
        $filename  = '';
        $rnd       = '';

        $i = 0;
        do {
            $filename  = \sha1(((string) $request->getData('image')) . $rnd);
            $filename .= '.' . $extension;

            $rnd = \mt_rand();

            ++$i;
        } while (\is_file($path . '/' . $filename) && $i < 10000);

        // protection against infinite loop
        if ($i >= 10000) {
            $this->fillJsonResponse($request, $response, NotificationLevel::ERROR, 'Draw', 'Draw failed.', null);
            return;
        }

        $fullPath = __DIR__ . '/../../../' . $path . '/' . $filename;

        $this->createLocalFile($fullPath, (string) $request->getData('image'));

        $status = [
            'path'      => $path,
            'filename'  => $filename,
            'name'      => (string) $request->getData('title'),
            'size'      => File::size($fullPath),
            'extension' => $extension,
            'status'    => UploadStatus::OK,
        ];

        $media = MediaController::createDbEntry($status, $request->header->account);
        $draw  = $media !== null ? DrawImage::fromMedia($media) : null;

        $this->createModel($request->header->account, $draw, DrawImageMapper::class, 'draw', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Draw', 'Draw successfully created.', $draw);
    }

    /**
     * Create local image file
     *
     * @param string $outputPath Output path
     * @param string $raw        Base64 encoded image string
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private function createLocalFile(string $outputPath, string $raw) : bool
    {
        $imageData = ImageUtils::decodeBase64Image($raw);
        File::put($outputPath, $imageData);

        return true;
    }
}
