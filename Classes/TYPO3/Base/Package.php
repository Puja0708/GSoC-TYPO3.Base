<?php
namespace TYPO3\Base\Package;

use Files;

/**
 * A Package
 *
 * @api
 */
 class Package{

   /**
	 * Unique key of this package. Example for the package: "TYPO3.Base"
	 * @var string
	 */
	protected $packageKey;

	/**
	 * @var string
	 */
	protected $manifestPath;

	/**
	 * Full path to this package's main directory
	 * @var string
	 */
	protected $packagePath;

	/**
	 * Full path to this package's PSR-0 class loader entry point
	 * @var string
	 */
	protected $classesPath;

	/**
	 * If this package is protected and therefore cannot be deactivated or deleted
	 * @var boolean
	 * @api
	 */
	protected $protected = FALSE;

	/**
	 * @var \stdClass
	 */
	protected $composerManifest;

	/**
	 * Meta information about this package
	 * @var String
	 */
	protected $packageMetaData;

	/**
	 * Names and relative paths (to this package directory) of files containing classes
	 * @var array
	 */
	protected $classFiles;

	/**
	 * The namespace of the classes contained in this package
	 * @var string
	 */
	protected $namespace;

	/**
	 * If enabled, the files in the Classes directory are registered and Reflection, Dependency Injection, AOP etc. are supported.
	 * Disable this flag if you don't need object management for your package and want to save some memory.
	 * @var boolean
	 * @api
	 */
	protected $objectManagementEnabled = TRUE;

	/**
	 * @var String
	 */
	protected $packageManager;

	/**
	 * Returns the array of filenames of the class files
	 *
	 * @return array An array of class names (key) and their filename, including the relative path to the package's directory
	 * @api
	 */
	public function getClassFiles(){
		;
	}

	/**
	 * Returns the package key of this package.
	 *
	 * @return string
	 * @api
	 */
	public function getPackageKey(){
		;
	}

	/**
	 * Returns the PHP namespace of classes in this package.
	 *
	 * @return string
	 * @api
	 */
	public function getNamespace(){
		;
	}

	/**
	 * Tells if this package is protected and therefore cannot be deactivated or deleted
	 *
	 * @return boolean
	 * @api
	 */
	public function isProtected(){
		;
	}

	/**
	 * Tells if files in the Classes directory should be registered and object management enabled for this package.
	 *
	 * @return boolean
	 */
	public function isObjectManagementEnabled(){
		;
	}

	/**
	 * Sets the protection flag of the package
	 *
	 * @param boolean $protected TRUE if the package should be protected, otherwise FALSE
	 * @return void
	 * @api
	 */
	public function setProtected($protected){
		;
	}

	/**
	 * Returns the full path to this package's main directory
	 *
	 * @return string Path to this package's main directory
	 * @api
	 */
	public function getPackagePath(){
		;
	}

	/**
	 * Returns the full path to this package's Classes directory
	 *
	 * @return string Path to this package's Classes directory
	 * @api
	 */
	public function getClassesPath(){
		;
	}

	/**
	 * Returns the full path to the package's classes namespace entry path,
	 * e.g. "My.Package/ClassesPath/My/Package/"
	 *
	 * @return string Path to this package's Classes directory
	 * @api
	 */
	public function getClassesNamespaceEntryPath(){
		;
	}

	/**
	 * Returns the full path to this package's Resources directory
	 *
	 * @return string Path to this package's Resources directory
	 * @api
	 */
	public function getResourcesPath(){
		;
	}

	/**
	 * Returns the full path to this package's Configuration directory
	 *
	 * @return string Path to this package's Configuration directory
	 * @api
	 */
	public function getConfigurationPath(){
		;
	}

	/**
	 * Returns the full path to this package's Package.xml file
	 *
	 * @return string Path to this package's Package.xml file
	 * @api
	 */
	public function getMetaPath(){
		;
	}

	/**
	 * Returns the full path to the package's documentation directory
	 *
	 * @return string Full path to the package's documentation directory
	 * @api
	 */
	public function getDocumentationPath(){
		;
	}



	/**
	 * Constructor
	 *
	 * @param string $packageManager the package manager which knows this package
	 * @param string $packageKey Key of this package
	 * @param string $packagePath Absolute path to the location of the package's composer manifest
	 * @param string $classesPath Path the classes of the package are in, relative to $packagePath. Optional, read from Composer manifest if not set.
	 * @param string $manifestPath Path the composer manifest of the package, relative to $packagePath. Optional, defaults to ''.
	 * @throws \TYPO3\Base\Exception\InvalidPackageKeyException if an invalid package key was passed
	 * @throws \TYPO3\Base\Exception\InvalidPackagePathException if an invalid package path was passed
	 * @throws \TYPO3\Base\Exception\InvalidPackageManifestException if no composer manifest file could be found
	 */
	public function __construct($packageKey, $packageManager, $packagePath, $classesPath=NULL, $manifestPath='') {

		if (preg_match(self::PATTERN_MATCH_PACKAGEKEY, $packageKey) !== 1) {
			throw new \TYPO3\Base\Exception\InvalidPackageKeyException('"' . $packageKey . '" is not a valid package key.', 1217959510);
		}
		if (!(is_dir($packagePath) || (Files::is_link($packagePath) && is_dir(Files::getNormalizedPath($packagePath))))) {
			throw new \TYPO3\Base\Exception\InvalidPackagePathException(sprintf('Tried to instantiate a package object for package "%s" with a non-existing package path "%s". Either the package does not exist anymore, or the code creating this object contains an error.', $packageKey, $packagePath), 1166631889);
		}
		if (substr($packagePath, -1, 1) !== '/') {
			throw new \TYPO3\Base\Exception\InvalidPackagePathException(sprintf('The package path "%s" provided for package "%s" has no trailing forward slash.', $packagePath, $packageKey), 1166633720);
		}
		if (substr($classesPath, 1, 1) === '/') {
			throw new \TYPO3\Base\Exception\InvalidPackagePathException(sprintf('The package classes path provided for package "%s" has a leading forward slash.', $packageKey), 1334841320);
		}
		if (!file_exists($packagePath . $manifestPath . 'composer.json')) {
			throw new \TYPO3\Base\Exception\InvalidPackageManifestException(sprintf('No composer manifest file found for package "%s". Please create one at "%scomposer.json".', $packageKey, $manifestPath), 1349776393);
		}

		$this->packageManager = $packageManager;
		$this->manifestPath = $manifestPath;
		$this->packageKey = $packageKey;
		$this->packagePath = Files::getNormalizedPath($packagePath);
		if (isset($this->getComposerManifest()->autoload->{'psr-0'})) {
			$this->classesPath = Files::getNormalizedPath($this->packagePath . $this->getComposerManifest()->autoload->{'psr-0'}->{$this->getNamespace()});
		} else {
			$this->classesPath = Files::getNormalizedPath($this->packagePath . $classesPath);
		}

 }


	/**
	 * Deletes all files, directories and subdirectories from the specified
	 * directory. The passed directory itself won't be deleted though.
	 *
	 * @param string $path Path to the directory which shall be emptied.
	 * @return void
	 * @throws Exception
	 * @see removeDirectoryRecursively()
	 */
	static public function emptyDirectoryRecursively($path) {
		if (!is_dir($path)) {
			throw new \TYPO3\Base\Exception('"' . $path . '" is no directory.', 1169047616);
		}

		if (self::is_link($path)) {
			if (self::unlink($path) !== TRUE) {
				throw new \TYPO3\Base\Exception('Could not unlink symbolic link "' . $path . '".', 1323697654);
			}
		} else {
			$directoryIterator = new \RecursiveDirectoryIterator($path);
			foreach ($directoryIterator as $fileInfo) {
				if (!$fileInfo->isDir()) {
					if (self::unlink($fileInfo->getPathname()) !== TRUE) {
						throw new \TYPO3\Base\Exception('Could not unlink file "' . $fileInfo->getPathname() . '".', 1169047619);
					}
				} elseif (!$directoryIterator->isDot()) {
					self::removeDirectoryRecursively($fileInfo->getPathname());
				}
			}
		}
	}

	/**
	 * Deletes all files, directories and subdirectories from the specified
	 * directory. Contrary to emptyDirectoryRecursively() this function will
	 * also finally remove the emptied directory.
	 *
	 * @param  string $path Path to the directory which shall be removed completely.
	 * @return void
	 * @throws Exception
	 * @see emptyDirectoryRecursively()
	 */
	static public function removeDirectoryRecursively($path) {
		if (self::is_link($path)) {
			if (self::unlink($path) !== TRUE) {
				throw new \TYPO3\Base\Exception('Could not unlink symbolic link "' . $path . '".', 1316000297);
			}
		} else {
			self::emptyDirectoryRecursively($path);
			try {
				if (rmdir($path) !== TRUE) {
					throw new \TYPO3\Base\Exception('Could not remove directory "' . $path . '".', 1316000298);
				}
			} catch (\Exception $exception) {
				throw new \TYPO3\Base\Exception('Could not remove directory "' . $path . '".', 1323961907);
			}
		}
	}

	/**
	 * Creates a directory specified by $path. If the parent directories
	 * don't exist yet, they will be created as well.
	 *
	 * @param string $path Path to the directory which shall be created
	 * @return void
	 * @throws Exception
	 * @todo Make mode configurable / make umask configurable
	 */
	static public function createDirectoryRecursively($path) {
		if (substr($path, -2) === '/.') {
			$path = substr($path, 0, -1);
		}
		if (is_file($path)) {
			throw new \TYPO3\Base\Exception('Could not create directory "' . $path . '", because a file with that name exists!', 1349340620);
		}
		if (!is_dir($path) && strlen($path) > 0) {
			$oldMask = umask(000);
			mkdir($path, 0777, TRUE);
			umask($oldMask);
			if (!is_dir($path)) {
				throw new \TYPO3\Base\Exception('Could not create directory "' . $path . '"!', 1170251400);
			}
		}
	}

	/**
	 * Copies the contents of the source directory to the target directory.
	 * $targetDirectory will be created if it does not exist.
	 *
	 * If $keepExistingFiles is TRUE, this will keep files already present
	 * in the target location. It defaults to FALSE.
	 *
	 * If $copyDotFiles is TRUE, this will copy files whose name begin with
	 * a dot. It defaults to FALSE.
	 *
	 * @param string $sourceDirectory
	 * @param string $targetDirectory
	 * @param boolean $keepExistingFiles
	 * @param boolean $copyDotFiles
	 * @return void
	 * @throws Exception
	 */
	static public function copyDirectoryRecursively($sourceDirectory, $targetDirectory, $keepExistingFiles = FALSE, $copyDotFiles = FALSE) {
		if (!is_dir($sourceDirectory)) {
			throw new \TYPO3\Base\Exception('"' . $sourceDirectory . '" is no directory.', 1235428779);
		}

		self::createDirectoryRecursively($targetDirectory);
		if (!is_dir($targetDirectory)) {
			throw new \TYPO3\Base\Exception('"' . $targetDirectory . '" is no directory.', 1235428780);
		}

		$sourceFilenames = self::readDirectoryRecursively($sourceDirectory, NULL, FALSE, $copyDotFiles);
		foreach ($sourceFilenames as $filename) {
			$relativeFilename = str_replace($sourceDirectory, '', $filename);
			self::createDirectoryRecursively($targetDirectory . dirname($relativeFilename));
			$targetPathAndFilename = self::concatenatePaths(array($targetDirectory, $relativeFilename));
			if ($keepExistingFiles === FALSE || !file_exists($targetPathAndFilename)) {
				copy($filename, $targetPathAndFilename);
			}
		}
	}

	/**
	 * An enhanced version of file_get_contents which intercepts the warning
	 * issued by the original function if a file could not be loaded.
	 *
	 * @param string $pathAndFilename Path and name of the file to load
	 * @param integer $flags (optional) ORed flags using PHP's FILE_* constants (see manual of file_get_contents).
	 * @param resource $context (optional) A context resource created by stream_context_create()
	 * @param integer $offset (optional) Offset where reading of the file starts.
	 * @param integer $maximumLength (optional) Maximum length to read. Default is -1 (no limit)
	 * @return mixed The file content as a string or FALSE if the file could not be opened.
	 */
	static public function getFileContents($pathAndFilename, $flags = 0, $context = NULL, $offset = -1, $maximumLength = -1) {
		if ($flags === TRUE) {
			$flags = FILE_USE_INCLUDE_PATH;
		}
		try {
			if ($maximumLength > -1) {
				$content = file_get_contents($pathAndFilename, $flags, $context, $offset, $maximumLength);
			} else {
				$content = file_get_contents($pathAndFilename, $flags, $context, $offset);
			}
		} catch (\TYPO3\Base\Exception $ignoredException) {
			$content = FALSE;
		}
		return $content;
	}

	/**
	 * Returns a human-readable message for the given PHP file upload error
	 * constant.
	 *
	 * @param integer $errorCode One of the UPLOAD_ERR_ constants
	 * @return string
	 */
	static public function getUploadErrorMessage($errorCode) {
		switch ($errorCode) {
			case \UPLOAD_ERR_INI_SIZE:
				return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
			case \UPLOAD_ERR_FORM_SIZE:
				return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
			case \UPLOAD_ERR_PARTIAL:
				return 'The uploaded file was only partially uploaded';
			case \UPLOAD_ERR_NO_FILE:
				return 'No file was uploaded';
			case \UPLOAD_ERR_NO_TMP_DIR:
				return 'Missing a temporary folder';
			case \UPLOAD_ERR_CANT_WRITE:
				return 'Failed to write file to disk';
			case \UPLOAD_ERR_EXTENSION:
				return 'File upload stopped by extension';
			default:
				return 'Unknown upload error';
		}
	}

	/**
	 * A version of is_link() that works on Windows too
	 * @see http://www.php.net/is_link
	 *
	 * If http://bugs.php.net/bug.php?id=51766 gets fixed we can drop this.
	 *
	 * @param string $pathAndFilename Path and name of the file or directory
	 * @return boolean TRUE if the path exists and is a symbolic link, FALSE otherwise
	 */
	static public function is_link($pathAndFilename) {
			// if not on Windows, call PHPs own is_link() function
		if (DIRECTORY_SEPARATOR === '/') {
			return \is_link($pathAndFilename);
		}
		if (!file_exists($pathAndFilename)) {
			return FALSE;
		}
		$normalizedPathAndFilename = strtolower(rtrim(self::getUnixStylePath($pathAndFilename), '/'));
		$normalizedTargetPathAndFilename = strtolower(self::getUnixStylePath(realpath($pathAndFilename)));
		if ($normalizedTargetPathAndFilename === '') {
			return FALSE;
		}
		return $normalizedPathAndFilename !== $normalizedTargetPathAndFilename;
	}

	/**
	 * A version of unlink() that works on Windows regardless on the symlink type (file/directory)
	 *
	 * @param string $pathAndFilename Path and name of the file or directory
	 * @return boolean TRUE if file/directory was removed successfully
	 */
	static public function unlink($pathAndFilename) {
		try {
				// if not on Windows, call PHPs own unlink() function
			if (DIRECTORY_SEPARATOR === '/' || is_file($pathAndFilename)) {
				return @\unlink($pathAndFilename);
			}
			return rmdir($pathAndFilename);
		} catch (\Exception $exception) {
			return FALSE;
		}
	}
}
?>
