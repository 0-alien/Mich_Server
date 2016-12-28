<?php
/**
 * @SWG\Swagger(
 *
 *   basePath="/",
 *   schemes={"http", "https"},
 *
 *   @SWG\Info(
 *     version="1.0.0",
 *     title="Swagger 2.0 - MICH Server",
 *     description="",
 *     @SWG\Contact(name=""),
 *     @SWG\License(name="")
 *   ),
 *
 *
 *   @SWG\Definition(
 *     definition="errorModel",
 *     required={"code", "message"},
 *
 *     @SWG\Property(
 *       property="code",
 *       type="integer",
 *       format="int32"
 *     ),
 *
 *     @SWG\Property(
 *       property="message",
 *       type="string"
 *     )
 *   ),
 *
 *
 *   @SWG\Definition(
 *     definition="successModel",
 *     required={"code", "message", "data"},
 *
 *     @SWG\Property(
 *       property="code",
 *       type="integer",
 *       format="int32"
 *     ),
 *
 *     @SWG\Property(
 *       property="message",
 *       type="string"
 *     ),
 *
 *     @SWG\Property(
 *       property="data",
 *       type="object"
 *     )
 *   )
 * )
 */