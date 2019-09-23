defmodule Mix.Tasks.PapercutSummary.Get do
  use Mix.Task

  @shortdoc "Simply calls the TeamdynamixTv.PapercutSummary.get/0 function."
  def run(_args) do
    # Start up our app before we access the database.
    Mix.Task.run("app.start")

    TeamdynamixTv.PapercutSummary.get()
  end
end
