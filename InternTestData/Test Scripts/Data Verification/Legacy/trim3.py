import sys
import json
from numpy import*
#from pdb import *;



#####################################################
### EDIT THIS VARIABLE FOR OUTPUT FILE		#####
 						#####
outputfileless = "trimless.csv"			#####
outputfilegreater = "trimgreater.csv"		#####
### EDIT outputfile to change the name of the output file. needs to be within quotes
#####################################################

if len(sys.argv) > 1:
    line_generator = open(sys.argv[1])
else:
    line_generator = sys.stdin

def find_value(ordered_pairs):
    d = {}
    for k, v in ordered_pairs:
        if k == "value":
           d.setdefault(k, []).append(v)
        else:
           d[k] = v
    return d

def ifequal(iterator):
	return len(set(iterator)) <= 1
	
###################################################################################
# sets varaiables and starts first 5 entries of csv printout array

for line in line_generator:
    
    bool1 = True
    bool2 = True
    bool3 = True 	
    n=0
    m=0
    value1 = []
    value2 = []
    
    expression1 = []
    
    csv = []

    line_object = json.loads(line) #object_pairs_hook=find_value) 
    value = str(line_object["value"])  
    entityId = str(line_object["entityId"])
    FY = str(line_object["FY"])

    FQ = str(line_object["FQ"])
    
   


    try:
    	elementName = str(line_object["elementName"])
    except KeyError, e:
	elementName = "invalid elementName"

    csv = [entityId, FY,FQ, value, elementName]

#####################################################################################
    while bool1 == True :
	try: 
		value1.append(line_object["otherTermResults"][n]["value"])
		expression1.append(line_object["otherTermResults"][n]["expression"])
		
    	except IndexError:
   		bool1= False
	except KeyError, e:
		bool1 = False


	n+=1

    while bool2 == True: 
 	
	try: 
		value2.append(line_object["dimensionalFacts"][m]["value"])
		
		
	except IndexError:
		bool2 = False
		
	except KeyError, e:
		bool2 = False
		
		
	m+=1
######################################################################################

		# CREATES VALUE1 and expression1 and 2 arrays
    csv.append("OTHRTERMSVAL")    # REMOVE THIS LINE IF YOU DONT WANT IT TO TELL YOU WHEN IT IS IN OTHERTERMSVAL
    for i in range(len(value1)):
	try:
		csv.append(str(value1[i]))
		csv.append(str(expression1[i]))
	except IndexError:
		csv.append("invalid value1")

    csv.append("DIMFACTSVALS")    # REMOVE THIS LINE IF YOU DONT WANT IT TO TELL YOU WHEN IT IS IN DIMFACTSVALS
    for j in range(len(value2)):
	try:
		csv.append(str(value2[j]))
		
	except IndexError:
		csv.append("invalid value2")
    
############################################################################

		# WRITES CSV TO FILE AND STRIP UNNECCESSARY CHARACTERS
   
    csv = str(csv).replace("'", "")
    csv = csv.replace(" (" , "")
    csv = csv.replace(")" , "")
    csv = csv.replace(",,", ",")
    csv = csv.replace("[", "")
    csv = csv.replace("]", "")
    csv = csv.replace("{", "")
    csv = csv.replace("}", "") 
    #print(value1, value2)    
    
    
    try:
	
	if (int(value) < min(value1)):
	    myfile = open(outputfileless, "a")	
	    myfile.write(csv)
            myfile.write("\n")
            myfile.close()
        elif (int(value) > min(value1)):
	    myfile = open(outputfilegreater, "a")
            myfile.write(csv)
 	    myfile.write("\n")
	    myfile.close()
    except ValueError, e:
        l = 1+1 
    
    

###########################################################################




# IT is of the form print(entityId, FY, FQ, value, elementName, expression1[n], value1[n], expression1[n+1], value1[n+1} ..... expression2[n], value2[n], expression2[n+1], value2[n+1]  
    
    		


