require 'peach'
require 'fileutils'
require 'rake/packagetask'
require 'tempfile'

task :default do
  puts "You didn't tell me what to do. Please run this for your options:"
  puts "    rake -T"
end

desc 'This generates tags for Omeka and BagIt.'
task :tags do
  sh %{ctags -R ../..}
end

desc "Updates the version in the 'plugin.ini' file. If given the version
parameter, it also updates the version in the 'version' file. Before updating
the metadata files."

task :version, [:version] do |t, args|
  if args[:version].nil?
    version = IO.readlines('version')[0].strip
  else
    version = args[:version]
    IO.write('version', version, :mode => 'w')
  end

  puts "updating plugin.ini to #{version}"

  tmp = Tempfile.new 'features'
  tmp.close
  puts "TMP = <#{tmp.path.inspect}>"

  FileUtils.mv 'plugin.ini', tmp.path, :verbose => true
  sh %{cat #{tmp.path} | sed -e 's/^version=".*"/version="#{version}"/' > plugin.ini}
end

class PackageTask < Rake::PackageTask
  def package_dir_path()
    "#{package_dir}/#{@name}"
  end
  def package_name
    @name
  end
  def basename
    @version ? "#{@name}-#{@version}" : @name
  end
  def tar_bz2_file
    "#{basename}.tar.bz2"
  end
  def tar_gz_file
    "#{basename}.tar.gz"
  end
  def tgz_file
    "#{basename}.tgz"
  end
  def zip_file
    "#{basename}.zip"
  end
end

PackageTask.new('BagIt') do |p|
  p.version     = IO.readlines('version')[0].strip
  p.need_tar_gz = true
  p.need_zip    = true

  p.package_files.include('BagItPlugin.php')
  p.package_files.include('bagtmp/.empty')
  p.package_files.include('controllers/*.php')
  p.package_files.include('helpers/*.php')
  p.package_files.include('lib/**/*.php')
  p.package_files.exclude('lib/**/test/**/*.php')
  p.package_files.include('lib/**/*.mkd')
  p.package_files.include('lib/**/LICENSE')
  p.package_files.include('models/**.php')
  p.package_files.include('plugin.*')
  p.package_files.include('README.md')
  p.package_files.include('routes.ini')
  p.package_files.include('views/**/*.css')
  p.package_files.include('views/**/*.php')
  p.package_files.include('LICENSE')
end

