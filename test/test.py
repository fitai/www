#!/usr/bin/python

import sys, getopt


# This function should get called by calling the file
# If any flags are passed, use getopt to get them
def main(args):
    print 'test function'
    print 'received args {}'.format(args)


# Receives initial ping to file
if __name__ == '__main__':
    main(sys.argv[1:])
