PACKAGE_VERSION=$(cat ../adventskalender/package.json | grep version | head -1 | awk -F: '{ print $2 }' | sed 's/[",]//g' | tr -d '[[:space:]]')
docker login -u $1 -p $2
docker push hrueger/adventskalender:v$PACKAGE_VERSION