
PROJECT_DIR=${PWD};

git submodule add git://github.com/obullo/$1.git packages/$1
git submodule init
git submodule update

cd $PROJECT_DIR
echo 'commit for main project';
git commit -am 'added new submodule'
git push origin master

sudo chmod -R 777 $PROJECT_DIR
rm -rf $PROJECT_DIR/packages/$1/README.md

# find the package.json and move it to packages/$1
find $PROJECT_DIR/mtemp/$1 -name package.json -exec cp {} $PROJECT_DIR/packages/$1 \;
find $PROJECT_DIR/mtemp/$1 -type d -name releases -exec cp {} -ar $PROJECT_DIR/packages/$1 \;

echo 'commit for submodule';
cd $PROJECT_DIR/packages/$1;
git add package.json
git add releases
git commit -am 'added new files'
git remote set-url origin git@github.com:obullo/$1.git
git push origin master

cd $PROJECT_DIR
git reset HEAD
echo 'commit for main project';
git commit -am 'added new submodule'
git push origin master