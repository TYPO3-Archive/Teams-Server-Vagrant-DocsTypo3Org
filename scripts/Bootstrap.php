<?php

/**
 * Bootstrap environment.
 */
class Bootstrap {

	/**
	 * Synchronize Opscode Cookbooks.
	 *
	 * @return void
	 */
	public function syncOpscodeCookbooks() {
		$settings = $this->getSettigs();

		foreach ($settings['community.opscode.com'] as $cookbook => $version) {

			$cookbook = new CookbookOpscode($cookbook, $version);
			if ($cookbook->isDirty()) {
				$cookbook->remove();
				$cookbook->download();
			}
		}
	}

	/**
	 * Synchronize Git Cookbooks.
	 *
	 * @return void
	 */
	public function syncGitCookbooks() {
		$settings = $this->getSettigs();

		foreach ($settings['git'] as $cookbook => $repository) {
			$cookbook = new CookbookGit($cookbook, $repository['url'], $repository['revision']);
			$cookbook->sync();
		}
	}

	/**
	 * @return array
	 */
	protected function getSettigs() {
		$settings = parse_ini_file(SETTING_FILE, true);
		return $settings;
	}
}