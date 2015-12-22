<?php

namespace cygnus\logging\config\builder;

interface Builder {

	public function build();

	public function buildFromJson($jsonObj, $envVars);

}