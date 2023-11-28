<?php

function getS3PathForEnv() {
    return config('app.env') === "local" || config('app.env') === "stage" ? config('app.aws_s3_path_stage') : config('app.aws_s3_path_prod');
}
