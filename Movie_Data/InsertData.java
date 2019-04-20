import com.univocity.parsers.common.*;
import com.univocity.parsers.common.processor.*;
import com.univocity.parsers.conversions.*;
import com.univocity.parsers.tsv.*;

import java.io.FileReader;
import java.util.*;

import java.sql.*;

public class InsertData {

    public static void main(String[] args) {

        //change these values depending on import
        boolean importNameBasics = false;
        boolean importTitleAKAs = false;
        boolean importTitleBasics = false;
        boolean importTitleCrew = false;
        boolean importTitleEpisodes = false;
        boolean importTitlePrinciples = false;
        boolean importTitleRatings = false;

        Connection con = null;

        try {
            //establish connection to database
            Class.forName("com.mysql.jdbc.Driver");
            con = DriverManager.getConnection("jdbc:mysql://nbarn.io:3306/movies","gresham","?072omLtrekL");
            if(!con.isClosed()) {
                Statement myStmt = null;
                ResultSet myRs = null;

                //create tsv parser
                TsvParserSettings settings = new TsvParserSettings();
                settings.getFormat().setLineSeparator("\n");
                settings.setMaxCharsPerColumn(100000); 
                TsvParser parser = new TsvParser(settings);


                /*
                *~~~IMPORT NAME BASICS~~~
                */
                if (importNameBasics) {
                    //read from tsv
                    parser.beginParsing(new FileReader("name_basics.tsv"));

                    //import into database
                    String[] row;
                    while ((row = parser.parseNext()) != null) {
                        myStmt = con.createStatement();
                        String query = "INSERT INTO NameBasics (nconst, primaryName, birthYear, deathYear, primaryProfession, knownForTitles) values (?, ?, ?, ?, ?, ?)";
                        PreparedStatement preparedStmt = con.prepareStatement(query);
                        preparedStmt.setString(1, row[0]);
                        preparedStmt.setString(2, row[1]);
                        preparedStmt.setInt(3, row[2]);
                        preparedStmt.setInt(4, row[3]);
                        preparedStmt.setString(5, row[4]);
                        preparedStmt.setString(6, row[5]);
                        preparedStmt.execute();
                        System.out.println(Arrays.toString(row));
                    }

                    parser.stopParsing();
                }
                /*
                *~~~END IMPORT~~~
                */


                /*
                *~~~IMPORT TITLE AKAS~~~
                */
                if (importTitleAKAs) {
                    //read from tsv
                    parser.beginParsing(new FileReader("title_akas.tsv"));

                    //import into database
                    String[] row;
                    while ((row = parser.parseNext()) != null) {
                        myStmt = con.createStatement();
                        String query = "INSERT INTO NameBasics (titleId, ordering, title, region, language, types, attributes, isOriginalTitle) values (?, ?, ?, ?, ?, ?, ?, ?)";
                        PreparedStatement preparedStmt = con.prepareStatement(query);
                        preparedStmt.setString(1, row[0]);
                        preparedStmt.setInt(2, row[1]);
                        preparedStmt.setString(3, row[2]);
                        preparedStmt.setString(4, row[3]);
                        preparedStmt.setString(5, row[4]);
                        preparedStmt.setString(6, row[5]);
                        preparedStmt.setString(7, row[6]);
                        preparedStmt.setInt(8, row[7]);
                        preparedStmt.execute();
                        System.out.println(Arrays.toString(row));
                    }

                    parser.stopParsing();
                }
                /*
                *~~~END IMPORT~~~
                */


                /*
                *~~~IMPORT TITLE BASICS~~~
                */
                if (importTitleBasics) {
                    //read from tsv
                    parser.beginParsing(new FileReader("title_basics.tsv"));

                    //import into database
                    String[] row;
                    while ((row = parser.parseNext()) != null) {
                        myStmt = con.createStatement();
                        String query = "INSERT INTO TitleBasics (tconst, titleType, primaryTitle, originalTitle, isAdult, startYear, endYear, runtimeMinutes, genres) values (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        PreparedStatement preparedStmt = con.prepareStatement(query);
                        preparedStmt.setString(1, row[0]);
                        preparedStmt.setString(2, row[1]);
                        preparedStmt.setString(3, row[2]);
                        preparedStmt.setString(4, row[3]);
                        preparedStmt.setInt(5, row[4]);
                        preparedStmt.setInt(6, row[5]);
                        preparedStmt.setInt(7, row[6]);
                        preparedStmt.setInt(8, row[7]);
                        preparedStmt.setString(9, row[8]);
                        preparedStmt.execute();
                        System.out.println(Arrays.toString(row));
                    }

                    parser.stopParsing();
                }
                /*
                *~~~END IMPORT~~~
                */


                /*
                *~~~IMPORT TITLE CREW~~~
                */
                if (importTitleCrew) {
                    //read from tsv
                    parser.beginParsing(new FileReader("title_crew.tsv"));

                    //import into database
                    String[] row;
                    while ((row = parser.parseNext()) != null) {
                        myStmt = con.createStatement();
                        String query = "INSERT INTO TitleCrew (tconst, directors, writers) values (?, ?, ?)";
                        PreparedStatement preparedStmt = con.prepareStatement(query);
                        preparedStmt.setString(1, row[0]);
                        preparedStmt.setString(2, row[1]);
                        preparedStmt.setString(3, row[2]);
                        preparedStmt.execute();
                        System.out.println(Arrays.toString(row));
                    }

                    parser.stopParsing();
                }
                /*
                *~~~END IMPORT~~~
                */


                /*
                *~~~IMPORT TITLE EPISODES~~~
                */
                if (importTitleEpisodes) {
                    //read from tsv
                    parser.beginParsing(new FileReader("title_episodes.tsv"));

                    //import into database
                    String[] row;
                    while ((row = parser.parseNext()) != null) {
                        myStmt = con.createStatement();
                        String query = "INSERT INTO TitleEpisodes (tconst, parentTconst, seasonNumber, episodeNumber) values (?, ?, ?, ?)";
                        PreparedStatement preparedStmt = con.prepareStatement(query);
                        preparedStmt.setString(1, row[0]);
                        preparedStmt.setString(2, row[1]);
                        preparedStmt.setInt(3, row[2]);
                        preparedStmt.setInt(4, row[3]);
                        preparedStmt.execute();
                        System.out.println(Arrays.toString(row));
                    }

                    parser.stopParsing();
                }
                /*
                *~~~END IMPORT~~~
                */


                /*
                *~~~IMPORT TITLE PRINCIPLES~~~
                */
                if (importTitlePrinciples) {
                    //read from tsv
                    parser.beginParsing(new FileReader("title_principles.tsv"));

                    //import into database
                    String[] row;
                    while ((row = parser.parseNext()) != null) {
                        myStmt = con.createStatement();
                        String query = "INSERT INTO TitlePrinciples (tconst, ordering, nconst, category, job, characters) values (?, ?, ?, ?, ?, ?)";
                        PreparedStatement preparedStmt = con.prepareStatement(query);
                        preparedStmt.setString(1, row[0]);
                        preparedStmt.setInt(2, row[1]);
                        preparedStmt.setString(3, row[2]);
                        preparedStmt.setString(4, row[3]);
                        preparedStmt.setString(5, row[4]);
                        preparedStmt.setString(6, row[5]);
                        preparedStmt.execute();
                        System.out.println(Arrays.toString(row));
                    }

                    parser.stopParsing();
                }
                /*
                *~~~END IMPORT~~~
                */


                /*
                *~~~IMPORT TITLE RATINGS~~~
                */
                if (importTitleRatings) {
                    //read from tsv
                    parser.beginParsing(new FileReader("title_ratings.tsv"));

                    //import into database
                    String[] row;
                    while ((row = parser.parseNext()) != null) {
                        myStmt = con.createStatement();
                        String query = "INSERT INTO TitleRatings (tconst, averageRating, numVotes) values (?, ?, ?)";
                        PreparedStatement preparedStmt = con.prepareStatement(query);
                        preparedStmt.setString(1, row[0]);
                        preparedStmt.setDouble(2, row[1]);
                        preparedStmt.setInt(3, row[2]);
                        preparedStmt.execute();
                        System.out.println(Arrays.toString(row));
                    }

                    parser.stopParsing();
                }
                /*
                *~~~END IMPORT~~~
                */
            }

        } catch(Exception e) {
            System.err.println("Exception: " + e.getMessage());

        } finally {
            try {
                if(con != null)
                con.close();
            } catch(SQLException e) {}
        } 
        
    }
}