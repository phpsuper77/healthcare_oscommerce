# CMP.PL - Copyleft darthmonk@tin.it
#
#   * 25 April 2003
#    - first version of cmp.pl
#
#   * 31 July 2003
#    - cmp doesn't ignore defines in lines beginning with one or more whitespace anymore
#    - fixed a bug due to the presence of \' in the values-side of a define()
#
#

use strict;
use Cwd;
use File::Find;
use File::Spec;

my $regex = '^\s*define\(\'([^\']+)\',.+';
if ((-f $ARGV[0]) && (-f $ARGV[1]) && ($ARGV[0] =~ /.+\.php$/) && ($ARGV[1] =~ /.+\.php$/))
  {
    print "$ARGV[0] <=> $ARGV[1]\n\n";
    compare_defines($ARGV[0],$ARGV[1]);
    exit;
  }
if (!(-d $ARGV[0] && -d $ARGV[1]))
  {
    usage();
  }

my $cwd = getcwd()."/";
my (%files_in_dir1,%files_in_dir2);
my $ptr_to_hash = \%files_in_dir1;
my @missing_in_dir2;
find({wanted => \&hash_file_paths},$ARGV[0]);
$ptr_to_hash = \%files_in_dir2;
find({wanted => \&hash_file_paths},$ARGV[1]);

foreach my $value (keys %files_in_dir1)
  {
    if ($files_in_dir2{$value})
      {
        print "$files_in_dir1{$value} <=> $files_in_dir2{$value}\n\n";
        compare_defines($files_in_dir1{$value},$files_in_dir2{$value});
        delete $files_in_dir2{$value};
      }
    else
      {
        push @missing_in_dir2, $files_in_dir1{$value};
      }
  }
if (@missing_in_dir2 > 0)
  {
    print "Files contained in $ARGV[0] but not in $ARGV[1]\n\n";
    print map {"\t$_\n"} @missing_in_dir2;
    print "\n";
  }
if (%files_in_dir2 > 0)
  {
    print "Files contained in $ARGV[1] but not in $ARGV[0]\n\n";
    print map {"\t$_\n"} values %files_in_dir2;
  }
exit;

############################################
sub hash_file_paths
############################################
  {
    my $abs_path = $cwd.$File::Find::name;
    if ((-f $abs_path) && ($File::Find::name =~ /^[^\/]+\/(.+\.php)$/))
      {
        my $rel_path = $1;	
        $ptr_to_hash->{$rel_path} = $abs_path;
      }
  }
############################################
sub compare_defines
############################################
  {
    open(FD1, "<$_[0]")
        or die("Can't open file: $_[0]");
    open(FD2, "<$_[1]")
        or die("Can't open file: $_[1]");

    my (%defines_in_file1, %defines_in_file2);

    my $line_counter = 0;
    while (<FD1>)
      {
        chomp;
        $line_counter++;
        if(/$regex/)
          {
            $defines_in_file1{$1} = $line_counter.": ".$_;
          }
      }
    close FD1 or die("Can't close file: $_[0]");

    $line_counter = 0;
    while (<FD2>)
      {
        chomp;
        $line_counter++;
        if(/$regex/)
          {
            $defines_in_file2{$1} = $line_counter.": ".$_;
          }
      }
    close FD2 or die("Can't close file: $_[1]");

    my @missing_in_file2;
    foreach my $define (keys %defines_in_file1)
      {
        if (!$defines_in_file2{$define})
          {
            push @missing_in_file2, $defines_in_file1{$define};
          }
      }

    my @missing_in_file1;
    foreach my $define (keys %defines_in_file2)
      {
        if (!$defines_in_file1{$define})
          {
            push @missing_in_file1, $defines_in_file2{$define};
          }
      }

    if (@missing_in_file1 > 0)
      {
        @missing_in_file1 = sort { $a <=> $b } @missing_in_file1;
        print "\tMissing lines in $_[0]. Line numbers relative to $_[1]:\n";
        foreach my $define (@missing_in_file1)
          {
            print "\t\t$define\n";
          }
        print "\n";
      }

    if (@missing_in_file2 > 0)
      {
        @missing_in_file2 = sort { $a <=> $b } @missing_in_file2;
        print "\tMissing lines in $_[1]. Line numbers relative to $_[0]:\n";
        foreach my $define (@missing_in_file2)
          {
            print "\t\t$define\n";
          }
        print "\n";
      }

    if ((@missing_in_file1 + @missing_in_file2) == 0)
      {
        print "\tNo differences found\n\n";
      }
  }

############################################
sub usage
############################################
  {
    select STDERR;
    print "\nUsage:\n";
    print "\tcmp language_directory1 language_directory2\n";
    print "\tor\n";
    print "\tperl cmp.pl file1.php file2.php\n\n";
    print "NOTE: please use relative paths when passing language directories as arguments to the program.\n\n";
    exit(1);
  }
