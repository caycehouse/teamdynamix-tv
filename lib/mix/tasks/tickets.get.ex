defmodule Mix.Tasks.Tickets.Get do
  use Mix.Task

  @shortdoc "Simply calls the TeamdynamixTv.Ticket.get/0 function."
  def run(_args) do
    # Start up our app before we access the database.
    Mix.Task.run("app.start")

    TeamdynamixTv.Ticket.get()
  end
end
