df <- read.csv("new_issue13.csv")
View(df)
library(plotly)
library(ggplot2)
str(df)

library(gridExtra)
#output pdf
pdf("stockSymbol_termName_TermResults.pdf", onefile = TRUE,width = 25,height = 15)


results <- "C:\\Users\\ruska\\Documents " 
stocksymbol <- unique(df$stockSymbol)

#creating list of unique stockSymbols 
term<- unique(df$termName)
#print(stocksymbol)

# create for loop to produce ggplot2 graphs 
for (i in seq_along(stocksymbol)){ 
  for (j in seq_along(term)){
    # create plot for each stockSymbol and each termName in df 
    
    plot <- 
      ggplot(subset(df, df$stockSymbol==stocksymbol[i] & df$termName==term[j]), 
             aes(FYFQ, value, group = stockSymbol,termName)) + 
      geom_line(size=2) +
      theme(text = element_text(size=15))+
      
      ggtitle(paste('StockSymbol: ',stocksymbol[i],', TermName: ', term[j], sep= ''))
              
      print(plot)
  
    #ggsave(plot, filename = paste(results, 
     #                       stocksymbol[i],term[j], ".pdf", sep=''), scale=2)
  }
}
grid.arrange(plot,newpage = TRUE)
dev.off()